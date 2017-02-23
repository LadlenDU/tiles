<?php

namespace app\core;

//use app\core\Web;
use app\core\Config;
use app\core\IFormModel;
use app\helpers\Helper;
use app\helpers\Db;

//use app\core\Html;

/**
 * Validator отвечает за проверку значений.
 *
 * @package app\core
 */
class Validator
{
    /** @var array Функция для получения значений полей. */
    protected $retrievalFunction = ['\\app\\core\\Web', 'getPostData'];

    /** @var \app\core\FormModel Класс модели, для которой проверяет значения валидатор. */
    protected $modelClass;

    /** @var string Строка описывающая последнюю ошибку. */
    protected $lastErrorStr;

    /** @var string Название последнего проваленного теста. */
    protected $lastErrorTest;

    /** @var array Массив сообщений валидатора (см. Config::inst()->validator_messages). */
    protected $vm;

    /** @var array Массив названий полей (см. $this->modelClass->attributeLabels()). */
    protected $al;

    public function __construct(IFormModel $modelClass, $retrievalFunction = false)
    {
        $this->modelClass = $modelClass;

        if ($retrievalFunction)
        {
            $this->retrievalFunction = $retrievalFunction;
        }

        $this->vm = Config::inst()->validator_messages;
        $this->al = $this->modelClass->attributeLabels();
    }

    /**
     * Возвращает значение поля используя функцию self::$retrievalFunction.
     *
     * @param string $name Название поля.
     * @return mixed
     */
    protected function gVal($name)
    {
        return call_user_func($this->retrievalFunction, $name);
    }

    /**
     * Устанавливает текст последней ошибки при $res == false (или снимает ошибку при положительном $res).
     *
     * @param bool $res Результат последней функции валидации.
     * @param string $msg Тест ошибки.
     */
    protected function setLastError($res, $msg)
    {
        $this->lastErrorStr = $res ? null : $msg;
    }

    /**
     * Возвращает текст последней ошибки или пустое значение если последняя функция проверки прошла успешно.
     *
     * @return string|null
     */
    public function getLastError()
    {
        return $this->lastErrorStr;
    }

    /**
     * Является ли значение заполненным, т. е. не преобразуется ли оно в пустую строку.
     * Таким образом нуль (integer) - не пустой.
     *
     * @param string $name Название поля.
     * @return bool false если значение пустое.
     */
    public function notEmpty($name)
    {
        $str = $this->gVal($name);
        $res = (!empty($str) || $str === '0');
        $this->setLastError($res, sprintf(_($this->vm['notEmpty']['err']), $this->al[$name]));
        return $res;
    }

    /**
     * Проверка длины строки - строка должна быть не меньше указанной.
     *
     * @param string $name Название поля.
     * @param int $length Проверяемая длина.
     * @return bool true если строка не меньше указанной длины.
     */
    public function lengthNotLess($name, $length)
    {
        $str = $this->gVal($name);
        $valLength = mb_strlen($str, Config::inst()->globalEncoding);
        $res = (!strlen($valLength) || $valLength >= $length);
        $this->setLastError(
            $res,
            sprintf(
                ngettext(
                    $this->vm['lengthNotLess']['err_n']['s'],
                    $this->vm['lengthNotLess']['err_n']['p'],
                    $length
                ),
                $this->al[$name],
                $length
            )
        );
        return $res;
    }

    /**
     * Проверка на существование поля в базе данных.
     *
     * @param string $name Название поля.
     * @param string $table Название проверяемой таблицы.
     * @param string $column Название проверяемой колонки.
     * @param array $except Название поля, которое надо исключить из проверки, и функция для получения его значения.
     * @return bool true если значение не в списке.
     */
    public function notInDbList($name, $table, $column, $except = null)
    {
        $val = $this->gVal($name);
        $sql = 'SELECT 1 FROM ' . Db::obj()->quoteName($table) . ' WHERE ' . Db::obj()->quoteName($column) . ' = :val';
        $qValues = [':val' => $val];
        if ($except)
        {
            $sql .= ' AND ' . Db::obj()->quoteName($except['field']) . ' != :except';
            $qValues[':except'] = $except['callable']();
        }
        $res = Db::obj()->rawSelectQuery($sql, $qValues);
        $res = !(bool)$res;
        $this->setLastError($res, sprintf(_($this->vm['notInDbList']['err']), $this->al[$name]));
        return $res;
    }

    /**
     * Проверяет одинаковы ли строки в двух полях.
     *
     * @param string $name Название первого поля.
     * @param string $nameCompare Название второго сравниваемого поля.
     * @return bool true если строки одинаковы.
     */
    public function equalStrings($name, $nameCompare)
    {
        $str = $this->gVal($name);
        $strCompare = $this->gVal($nameCompare);
        $res = (strcmp($str, $strCompare) == 0);
        $this->setLastError($res, sprintf(_($this->vm['equalStrings']['err']), $this->al[$nameCompare], $this->al[$name]));
        return $res;
    }

    /**
     * Проверка корректности адреса электронной почты.
     *
     * @param string $name Название поля.
     * @return bool false если формат email неправильный.
     */
    public function email($name)
    {
        $email = $this->gVal($name);
        $res = (!strlen($email) || (bool)filter_var($email, FILTER_VALIDATE_EMAIL));
        $this->setLastError($res, _($this->vm['email']['err']));
        return $res;
    }

    /**
     * Проверка корректности формата даты (например, не может быть 31 февраля).
     *
     * @param string $name Название поля.
     * @param string $dayName Название поля дня.
     * @param string $monthName Название поля месяца (начиная с 1).
     * @param string $yearName Название поля года.
     * Здесь за пустое значение приняты любые значения меньше 1,
     * причем ВСЕ значения должны быть пустыми для отмены проверки.
     * @return bool false если формат даты неправильный.
     */
    public function date($name, $dayName, $monthName, $yearName)
    {
        $day = $this->gVal($dayName);
        $month = $this->gVal($monthName);
        $year = $this->gVal($yearName);
        $res = (($day <= 0 && $month <= 0 && $year <= 0) || checkdate($month, $day, $year));
        $this->setLastError($res, _($this->vm['date']['err']));
        return $res;
    }

    /**
     * Проверка даты на то, что она предшествует заданной дате $date.
     *
     * @param int $day День.
     * @param int $month Месяц (начиная с 1).
     * @param int $year Год.
     * @param int $date [optional] Дата относительно которой проверять (если не указана то используется текущее время).
     * Здесь за пустое значение приняты любые значения меньше 1,
     * причем ВСЕ значения должны быть пустыми для отмены проверки.
     * @return bool false если дата не в прошлом.
     */
    //TODO: пока не будем проверять, не принципиально.
    /*public function datePast($day, $month, $year, $date = false)
    {
        if ($month <= 0 || $day <= 0 || $year <= 0)
        {
            return false;
        }
        $date = $date ?: time();
        return $date > mktime(0, 0, 0, $month, $day, $year);
    }*/

    /**
     * Проверка на максимально допустимый размер загруженного изображения.
     *
     * @param string $name Название элемента файла (элемента $_FILE или с подобной элементу $_FILE структурой).
     * @param int $maxSize Максимально допустимый размер файла.
     * @return bool false если размер изображения превышает максимальный.
     */
    public function imageMaxSize($name, $maxSize)
    {
        $fileInfo = $this->gVal($name);

        if (empty($fileInfo))
        {
            return true;
        }

        $res = false;
        $fSize = 0;
        $errMsg = '';

        switch ($fileInfo['error'])
        {
            case UPLOAD_ERR_NO_FILE:
            {
                $res = true;
            }
                break;
            case UPLOAD_ERR_OK:
            {
                $fSize = filesize($fileInfo['tmp_name']);
                $res = ($fSize !== false && $fSize <= $maxSize);
            }
                break;
            case UPLOAD_ERR_INI_SIZE:
            {
                $maxSize = Helper::shorthandNotationToBytes(ini_get('upload_max_filesize'));
            }
                break;
            case UPLOAD_ERR_FORM_SIZE:
            {
                // Ничего не делаем.
            }
                break;
            default:
            {
                $errMsg = _('Error occured: ') . $fileInfo['error'];
            }
                break;
        }

        if (!$errMsg)
        {
            $errMsg = sprintf(
                ngettext(
                    $this->vm['imageMaxSize']['err_n']['s'],
                    $this->vm['imageMaxSize']['err_n']['p'],
                    $maxSize + 1
                ),
                $maxSize + 1
            );

            if ($fSize)
            {
                $errMsg .= ' ' . sprintf(
                        _($this->vm['imageMaxSize']['your_image_size']),
                        $fSize
                    );
            }
        }

        $this->setLastError($res, $errMsg);

        return $res;
    }

    /**
     * Проверка на допустимый тип изображения.
     *
     * @param string $name Название элемента файла (элемента $_FILE или с подобной элементу $_FILE структурой).
     * @param string[] $typesExt расширение изображения.
     * @param string[] $typesMIME MIME тип изображения.
     * @return bool false если MIME тип не поддерживается.
     */
    public function imageMIME($name, $typesExt, $typesMIME)
    {
        $fileInfo = $this->gVal($name);
        if (empty($fileInfo) || empty($fileInfo['tmp_name']))
        {
            return true;
        }
        $iType = exif_imagetype($fileInfo['tmp_name']);
        $res = ($iType !== false && in_array(image_type_to_mime_type($iType), $typesMIME));
        $this->setLastError($res, _($this->vm['imageMIME']['err_MIME']));
        return $res;
    }

    /**
     * Проверка на валидность телефона.
     *
     * @param string $name Название поля.
     * @return bool false если формат телефона не правильный.
     */
    public function phone($name)
    {
        $res = false;

        if ($phone = $this->gVal($name))
        {
            $url = 'https://lookups.twilio.com/v1/PhoneNumbers/' . rawurlencode($phone);
            $context = stream_context_create(
                array(
                    'http' => array(
                        //TODO: подумать что делать если телефон не прошел валидацию из-за ошибки, например, логина
                        'header' => "Authorization: Basic " . base64_encode(
                                //"AC7a67691fdff0d12f90f89b84ad9bc33a1:87d68ed8656de6a1072aa7a25767b396sdsd"
                                "AC7a67691fdf0d12f90f89b84ad9bc33a1:87d68ed8656de6a1072aa7a25767b396"
                            )
                    )
                )
            );

            $request = @file_get_contents($url, false, $context);
            if ($request === false)
            {
                $error = error_get_last();
                Helper::log('Phone validation error. Error message: ' . $error['message'], 'validate_phone');
            }
            else
            {
                if (($reqJson = json_decode($request)) && $reqJson->phone_number)
                {
                    $res = true;
                }
            }
        }
        else
        {
            $res = true;
        }

        $this->setLastError($res, _($this->vm['phone']['err']));

        return $res;
    }

    /**
     * Производит несколько проверок последовательно. Прекращает работу сразу при провале теста, не проводя оставшиеся.
     *
     * @param string $fieldName Название поля для проверки.
     * @param array $valParams Список, в котором ключи представляют названия тестов,
     * а значение - параметры для этого теста.
     * @return bool true если все проверки прошли успешно.
     */
    public function bunchValidation($fieldName, $valParams)
    {
        $ret = true;
        foreach ($valParams as $funcName => $funcVal)
        {
            $completeVal = (array)$funcVal;
            array_unshift($completeVal, $fieldName);

            $success = call_user_func_array("self::$funcName", $completeVal);
            if (!$success)
            {
                $this->lastErrorTest = $funcName;
                $ret = false;
                break;
            }
        }
        return $ret;
    }

}
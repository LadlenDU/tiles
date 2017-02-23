<?php

namespace app\models\forms;

use app\core\ContainerHSC;
use app\core\Config;
use app\core\Validator;
use app\helpers\Db;
use app\helpers\Helper;
use app\helpers\Image;
use app\core\IFormModel;
use app\models\User;
use app\models\Language;

class UserInfoForm implements IFormModel
{
    /** Папка для временных изображений. */
    const TEMP_WEB_IMAGES = 'img/tmp/';

    /** @var ContainerHSC Список ошибок при валидации. */
    protected $validationErrors;

    /** @var array Информация для валидации изображения. */
    protected $storedImageFileInfo = [];

    /** @var User Модель пользователя. */
    protected $userModel;

    /** @var int|null ID пользователя (если пользователь уже создан). */
    protected $uid;

    public function __construct($uid = null)
    {
        $this->uid = $uid;
        $this->prepareWebData();
    }

    /**
     * Подготовка данных.
     */
    protected function prepareWebData()
    {
        // Произошла отправка.
        if (isset($_POST['login']))
        {
            User::trimFields($_POST);

            // Преобразование javascript формата месяца в формат PHP.
            if ($_POST['birth']['month'] >= 0)
            {
                ++$_POST['birth']['month'];
            }
        }
    }

    /**
     * @inheritdoc
     */
    public static function attributeLabels()
    {
        return [
            'login' => _('Login'),
            'password' => _('Password'),
            'password_confirm' => _('Confirm password'),
            'first_name' => _('First name'),
            'last_name' => _('Last name'),
            'email' => _('E-mail'),
            'phone_mobile' => _('Mobile phone'),
            'birth_date' => _('Date of birth'),
            'gender' => _('Gender'),
            'country' => _('Country'),
            'address' => _('Address'),
            'image' => _('Image'),
        ];
    }

    /**
     * То же что UserInfoForm::attributeLabels(), но с двоеточием на конце метки.
     *
     * @return array
     */
    public static function attributeLabelsColon()
    {
        return [
            'login' => _('Login:'),
            'password' => _('Password:'),
            'password_confirm' => _('Confirm password:'),
            'first_name' => _('First name:'),
            'last_name' => _('Last name:'),
            'email' => _('E-mail:'),
            'phone_mobile' => _('Mobile phone:'),
            'birth_date' => _('Date of birth:'),
            'gender' => _('Gender:'),
            'country' => _('Country:'),
            'address' => _('Address:'),
            'image' => _('Image:'),
        ];
    }

    protected function getUserModel()
    {
        if (!$this->userModel)
        {
            $this->userModel = new User;
        }

        return $this->userModel;
    }

    /**
     * Возвращает общие поля - для пустой формы по умолчанию и подготавливает отправленные поля.
     *
     * @return ContainerHSC
     */
    public function getCommonFields()
    {
        $values = new ContainerHSC();

        // Список стран.
        $values->countries = Db::obj()->rawSelectQuery(
            'SELECT `code`, `name` FROM `country` WHERE `language_code` = :language_code ORDER BY `name` ASC',
            [':language_code' => Language::getLanguage()]
        );

        // Список месяцев.
        $values->monthsList = [];
        for ($m = 1; $m <= 12; $m++)
        {
            $values->monthsList[] = strftime("%B", mktime(0, 0, 0, $m));
        }

        // Список лет.
        $values->year = ['first' => date('Y') - 1, 'last' => date('Y') - 150];

        // Максимальные длины полей.
        $fieldsMaxSize = \app\helpers\DB::obj()->getTextColumnMaximumLength(
            'user',
            ['login', 'password_hash', 'first_name', 'last_name', 'email', 'phone_mobile', 'address']
        );

        $values->fieldMaxSizes = [];
        foreach ($fieldsMaxSize as $fmz)
        {
            $values->fieldMaxSizes[$fmz['COLUMN_NAME']] = $fmz['CHARACTER_MAXIMUM_LENGTH'];
        }

        $values->fieldMaxSizes['password'] = $values->fieldMaxSizes['password_hash'];
        $values->fieldMaxSizes['password_confirm'] = $values->fieldMaxSizes['password_hash'];

        // Максимальный размер изображежния.
        $values->maxFileSize = Config::inst()->user['image']['max_file_size'];

        #$values->noImage = new ContainerHSC;
        #$values->noImage->original = User::getNoImageParams();
        #$values->noImage->thumbnail = User::getNoImageParams();

        $values->userConf = Config::inst()->user;

        // Подписи к элементам.
        $values->formLabels = $this->attributeLabels();
        $values->formLabelsColon = $this->attributeLabelsColon();

        return $values;
    }

    /**
     * Проверка полей формы.
     *
     * @return bool
     */
    public function validate()
    {
        if (isset($_POST['login']))
        {
            $imgValidatorGetInfo = ['\\app\\core\\Web', 'getUploadedFileInfo'];

            // Изображение может быть уже загружено.
            if ($_FILES['image']['name'] == '' && $_FILES['image']['tmp_name'] == '' && !empty($_POST['stored_image']['original']))
            {
                //TODO: Возможно не самое лучшее решение.
                $this->storedImageFileInfo['image'] = [
                    'tmp_name' => Config::inst()->webDir . self::TEMP_WEB_IMAGES . basename(
                            $_POST['stored_image']['original']
                        ),
                    'error' => 0
                ];
                $imgValidatorGetInfo = [$this, 'getStoredImageFileInfo'];
            }

            $this->validationErrors = new ContainerHSC();
            foreach (Config::inst()->user['validators'] as $vName => $vParams)
            {
                if ($this->uid)
                {
                    // У имеющегося пользователя пароли не проверяем.
                    if ($vName == 'password' || $vName == 'password_confirm')
                    {
                        continue;
                    }
                }

                $cValidator = ($vName == 'image')
                    ? new Validator(
                        $this, $imgValidatorGetInfo
                    )     // Специальная обработка для изображений.
                    : new Validator($this);

                if (!$res = $cValidator->bunchValidation($vName, $vParams))
                {
                    $this->validationErrors[$vName] = ['last_error' => $cValidator->getLastError()];
                }
            }
        }
        else
        {
            return false;
        }

        return count($this->validationErrors) == 0;
    }

    public function getStoredImageFileInfo($name)
    {
        return $this->storedImageFileInfo[$name];
    }

    /**
     * Возвращает все поля, включая полученные и обработанные значения.
     *
     * @return ContainerHSC
     * @throws \Exception
     */
    public function getHtmlValues()
    {
        $params = $this->getCommonFields();

        if (isset($_POST['login']))
        {
            $params->values = $_POST;

            if ($this->uid)
            {
                $params->values->id = $this->uid;
            }

            if (count($this->validationErrors))
            {
                $params->saveErrors = $this->validationErrors;

                // Временное изображение.
                if ($this->validationErrors->c('image') == '')
                {
                    $params->storedImage = [];

                    if (is_uploaded_file($_FILES['image']['tmp_name']))
                    {
                        $tmpDir = Config::inst()->webDir . self::TEMP_WEB_IMAGES;
                        if ($thumbImgParams = $this->doThumb($tmpDir))
                        {
                            $params->storedImage->thumbnail
                                = $params->thumbImage->src
                                = '/' . self::TEMP_WEB_IMAGES . $thumbImgParams['name'];
                            $params->thumbImage->width = $thumbImgParams['width'];
                            $params->thumbImage->height = $thumbImgParams['height'];

                            $tmpImgUrl = self::TEMP_WEB_IMAGES . basename(
                                    $_FILES['image']['tmp_name']
                                ) . $thumbImgParams['ext'];

                            if (move_uploaded_file($_FILES['image']['tmp_name'], Config::inst()->webDir . $tmpImgUrl))
                            {
                                $params->storedImage->original = $params->image->src = "/$tmpImgUrl";
                            }
                        }
                    }
                    elseif (!empty($_POST['stored_image']['original']))
                    {
                        $params->storedImage->thumbnail = $params->thumbImage->src = $_POST['stored_image']['thumbnail'];
                        $params->storedImage->original = $params->image->src = $_POST['stored_image']['original'];
                    }
                }
            }
        }
        elseif ($this->uid)
        {
            $params->values = (new User)->getInfo($this->uid);
        }

        return $params;
    }

    /**
     * Преобразует путь URL к абсолютному пути временного изображения.
     *
     * @param string $urlPath URL-путь к временному изображению.
     * @return string
     */
    protected function storedImageUrlPathToAbsPath($urlPath)
    {
        return Config::inst()->webDir . self::TEMP_WEB_IMAGES . basename($urlPath);
    }

    /**
     * Создает тамбнейл временно загруженного файла изображения ($_FILES['image']['tmp_name']).
     *
     * @param string $thumbDir Директория куда помещать уменьшенное изображение.
     * @return array|bool Данные о созданном тамбнейле или false в случае неудачи.
     * @throws \Exception
     */
    protected function doThumb($thumbDir)
    {
        $file = $_FILES['image']['tmp_name'];
        $ext = '.' . Image::getExtension($file);    // Расширение по внутреннему типу.
        $thumbName = basename($file) . Image::THUMBNAIL_PREFIX . $ext;
        $thumbFile = $thumbDir . $thumbName;

        if ($img = Image::resizeImageReduce(
            $file,
            $thumbFile,
            Config::inst()->user['image']['max_thumb_size']
        )
        )
        {
            $img['path'] = $thumbFile;
            $img['name'] = $thumbName;
            $img['ext'] = $ext;
        }

        return $img;
    }

    public function save()
    {
        $ret = false;

        $info = [];
        $info['login'] = $_POST['login'];
        if (!$this->uid)
        {
            $info['password_hash'] = $_POST['password'];
        }
        $info['first_name'] = $_POST['first_name'];
        $info['last_name'] = $_POST['last_name'];
        $info['email'] = $_POST['email'];
        $info['phone_mobile'] = $_POST['phone_mobile'];
        if ($_POST['birth']['year'] == -1 && $_POST['birth']['month'] == -1 && $_POST['birth']['day'] == -1)
        {
            $info['birthday'] = -1;
        }
        else
        {
            $info['birthday'] = str_pad($_POST['birth']['year'], 4, '0', STR_PAD_LEFT) . '-'
                . str_pad($_POST['birth']['month'], 2, '0', STR_PAD_LEFT) . '-'
                . str_pad($_POST['birth']['day'], 2, '0', STR_PAD_LEFT);
        }
        $info['gender'] = $_POST['gender'];
        $info['country_code'] = $_POST['country'];
        $info['address'] = $_POST['address'];

        if (is_uploaded_file($_FILES['image']['tmp_name']))
        {
            $info['image'] = $_FILES['image']['tmp_name'];
            $info['image_thumb'] = ($thumb = $this->doThumb(sys_get_temp_dir() . '/')) ? $thumb['path'] : '';
        }
        elseif (!empty($_POST['stored_image']['original']))
        {
            $info['image'] = $this->storedImageUrlPathToAbsPath($_POST['stored_image']['original']);
            $info['image_thumb'] = $this->storedImageUrlPathToAbsPath($_POST['stored_image']['thumbnail']);
        }

        if ($this->uid)
        {
            $info['id'] = $this->uid;
            if ($this->getUserModel()->updateUser($info))
            {
                $ret = $this->uid;
            }
        }
        else
        {
            $ret = $this->getUserModel()->addUser($info);
        }

        if ($ret)
        {
            //TODO: возможно стоит удалять и при $ret == false
            if (!empty($info['image']))
            {
                @unlink($info['image']);
                @unlink($info['image_thumb']);
            }
        }

        return $ret;
    }
}
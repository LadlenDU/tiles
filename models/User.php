<?php

namespace app\models;

use app\core\Web;
use app\core\EntityModel;

/**
 * User модель пользователя.
 *
 * @package app\models
 */
class User extends EntityModel
{
    /** @var int Идентификатор пользователя. */
    #protected $id;

    public function __construct($id = null)
    {
        #$this->id = $id;
        parent::__construct();
    }

    public function entityName()
    {
        return '\\Entities\\User';
    }

    /**
     * Залогинить пользователя.
     *
     * @param string $login Логин пользователя.
     * @param string $password Пароль.
     * @return bool
     */
    public function logIn($login, $password)
    {
        $res = $this->createQueryBuilder('u')
            ->select('u.id')
            ->where('u.login = :login AND u.passwordHash = :password')
            ->setParameters(['login' => $login, 'password' => $password])
            ->getQuery()
            ->getOneOrNullResult();

        if ($res)
        {
            $_SESSION['logged_user']['id'] = $res['id'];
        }

        return (bool)$res;
    }

    /**
     * Разлогирование пользователя.
     */
    public static function logOut()
    {
        Web::startSession();
        unset($_SESSION['logged_user']['id']);
    }

    /**
     * Возвращает ID залогиненого пользователя или false если пользователь не залогинен.
     *
     * @return int|false
     */
    public static function loggedId()
    {
        Web::startSession();
        return empty($_SESSION['logged_user']['id']) ? false : $_SESSION['logged_user']['id'];
    }

    public function getLoggedUserInfo()
    {
        $info = false;

        if ($uId = self::loggedId())
        {
            $info = $this->getUserInfo($uId);
        }

        return $info;
    }

    public function getUserInfo($uId)
    {
        $res = $this->createQueryBuilder('u')
            ->where('u.id = :id')
            ->setParameter('id', $uId)
            ->getQuery()
            ->getOneOrNullResult();

        return $res;
    }
}

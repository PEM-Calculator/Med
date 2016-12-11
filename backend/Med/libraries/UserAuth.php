<?php

namespace Med\Libraries;

/**
 * Created by PhpStorm.
 * User: marina
 * Date: 06.12.2016
 * Time: 22:04
 */
class UserAuth extends \Phalcon\Mvc\User\Component
{
	/** @var \Med\Models\User */
	static $user = null;
	/** @var \Med\Models\UserLogin */
	static $userLogin = null;

	/**
	 * Запоминает значения
	 * @param \Med\Models\User $user
	 * @param \Med\Models\UserLogin $userLogin
	 */
	static function set(\Med\Models\User $user, \Med\Models\UserLogin $userLogin)
	{
		self::$user = $user;
		self::$userLogin = $userLogin;
	}

	/**
	 * Выход пользователя
	 */
	static function logout()
	{
		self::$user->logout(self::$userLogin);
	}
}
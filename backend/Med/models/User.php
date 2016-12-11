<?php

namespace Med\Models;

use \Med\Libraries\Errors;

class User extends \Med\Models\BaseModel
{
	public
		$username,
		$hash_password,
		$name,
		$midname,
		$surname,
		$date_birth,
		$data_contacts,
		$time_created,
		$time_updated,
		$time_logined,
		$time_last_logined,
		$is_admin,
		$is_locked,
		$time_locked;
		
	function assign(array $data, $dataColumnMap = NULL, $whiteList = NULL)
	{
		if(!isset($data['username']) || strlen($data['username']) < 5)
			Errors::e(Errors::ERR_NEED_USERNAME);
		
		if(!isset($data['password']) || strlen($data['password']) < 5)
			Errors::e(Errors::ERR_NEED_PASSWORD);
		
		$new_data = [
			'time_created' => time(),
			'username' => $data['username'],
			'hash_password' => self::generateHashPassword($data['username'], $data['password']),
		];
		
		# проверю необязательные поля
		foreach(['name', 'midname', 'surname'] as $f)
			if(isset($data[$f]))
				$new_data[$f] = $data[$f];
		
		if(isset($data['birth']))
			$new_data['date_birth'] = strtotime($data['birth']  . ' UTC');
		
		# проверю необязательные поля контактов
		if(isset($data['contacts'])) {
			$new_data['data_contacts'] = [];
			foreach(['email', 'phone', 'skype'] as $f)
				if(isset($data['contacts'][$f]))
					$new_data['data_contacts'][$f] = $data['contacts'][$f];
		}
		
		parent::assign($new_data, $dataColumnMap, $whiteList);
	}
	
	function isLocked()
	{
		return $this->is_locked;
	}
	
	/*
	 * Проверяет, совпадает ли пароль
	 */
	function checkPassword($password)
	{
		$hp = self::generateHashPassword($this->username, $password);
		return $hp === $this->hash_password;
	}
	
	/*
	 * Устанавливает новый пароль
	 */
	function setPassword(string $new_password)
	{
		$hash_password = self::generateHashPassword($this->username, $password);
	}
	
	/*
	 * Выполняет вход пользователя
	 * Возвращает объект UserLogin
	 */
	function login()
	{
		if($this->isLocked())
			return null;
		
		$userLogin = new UserLogin();
		$userLogin->id_user = $this->getId();
		$userLogin->time_login = time();
		$userLogin->time_live = time() + 24*60*60;
		$userLogin->hash = substr(md5(uniqid('', true)) . md5(time()), 0, 50);
		$userLogin->ip = $_SERVER['REMOTE_ADDR'];
		$userLogin->client_info = $_SERVER['HTTP_USER_AGENT'] ?? null;
		$userLogin->save();
		
		$this->time_last_logined = $this->time_logined;
		$this->time_logined = time();
		$this->update();
		
		$this->log(21);
		
		return $userLogin;
	}
	
	/*
	 * Выполняет выход пользователя
	 */
	function logout($userLogin)
	{
		$userLogin->time_logout = time();
		$userLogin->save();
		
		$this->log(22);
	}
	
	/**
	 * Создает новую log-запись о пользователе
	 * $action может принимать значения:
	 * 11 - Create user
	 * 12 - Delete user
	 * 21 - Login user
	 * 22 - Logout user
	 * 23 - Locked user
	 * 24 - Login user wrong
	 * 31 - Add user to group
	 * 32 - Del user from group
	 */
	private function log($action, $data = null, $id_group = null)
	{
		$sql = "INSERT INTO user_log (id_user, action, id_group, data) VALUES(:id_user, :action, :id_group, :data)";
		$query = $this->di->getDb()->execute($sql, [
			'id_user' => $this->getId(),
			'action' => $action,
			'id_group' => $id_group,
			'data' => $data,
		]);
	}
	
	function afterCreate()
	{
		$this->log(11);
	}
	
	function beforeDelete()
	{
		$this->log(12);
	}
	
	/**
	 * Генерирует хеш пароля для сохранения и проверки
	 */
	static function generateHashPassword($username, $password)
	{
		return md5($username . $password);
	}
}
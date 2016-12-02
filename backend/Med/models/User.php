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
		
	public function assign(array $data, $dataColumnMap = NULL, $whiteList = NULL)
	{
		if(!isset($data['username']) || strlen($data['username']) < 5)
			Errors::e(Errors::ERR_NEED_USERNAME);
		
		if(!isset($data['password']) || strlen($data['password']) < 5)
			Errors::e(Errors::ERR_NEED_PASSWORD);
		
		$new_data = [
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
	
	static public function generateHashPassword($username, $password)
	{
		return md5($username . $password);
	}
	
	public function setPassword(string $new_password)
	{
		$hash_password = self::generateHashPassword($this->username, $password);
	}
}
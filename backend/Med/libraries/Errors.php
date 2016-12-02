<?php

namespace Med\Libraries;

class Errors extends \Phalcon\Mvc\User\Component
{
	const ERR_NEED_DATA				= 0xE000;
	const ERR_NEED_USERNAME			= 0xE001;
	const ERR_NEED_PASSWORD			= 0xE002;
	
	const ERR_OBJECT_NOT_SAVED		= 0xE010;
	
	const ERR_FIELD_SET_DENIED		= 0xE020;
	const ERR_FIELD_SET_READONLY	= 0xE021;
	const ERR_FIELD_GET_UNKNOWN		= 0xE022;
	
	
	const ERROR_MESSAGES = [
		# 0xE000...
		self::ERR_NEED_DATA => 'Need data',
		self::ERR_NEED_USERNAME => 'Need username',
		self::ERR_NEED_PASSWORD => 'Need password',
		# 0xE010...
		self::ERR_OBJECT_NOT_SAVED => 'Object not saved with message "%s"',
		# 0xE020...
		self::ERR_FIELD_SET_DENIED => 'Cannot set unknown field "%s"',
		self::ERR_FIELD_SET_READONLY => 'Cannot set readonly field "%s"',
		self::ERR_FIELD_GET_UNKNOWN => 'Cannot get unknown field "%s"',
	];
	
	/*
	 * Метод генерирует ошибку прямо в коде
	 */
	static public function e($code, ...$args)
	{
		throw new \Exception(sprintf(self::ERROR_MESSAGES[$code], ...$args));
	}
}
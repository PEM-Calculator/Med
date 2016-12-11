<?php

namespace Med\Libraries;

class Errors extends \Phalcon\Mvc\User\Component
{
	// Требуется указать поле data с данными
	const ERR_NEED_DATA						= 0xE001;
	// Необходимо указать имя пользователя
	const ERR_NEED_USERNAME					= 0xE002;
	// Необходимо указать пароль
	const ERR_NEED_PASSWORD					= 0xE003;
	// Ошибка авторизациии
	const ERR_USERNAME_OR_PASSWORD_WRONG	= 0xE004;
	// Необходимо указать поле
	const ERR_NEED_FIELD_VALUE              = 0xE005;
	// Ошибка при заполнении поля
	const ERR_WRONG_FIELD_VALUE             = 0xE006;
	
	// Ошибка: не удалось сохранить объект
	const ERR_OBJECT_NOT_SAVED				= 0xE011;

	// Задача не создана или к ней нет доступа
	const ERR_TASK_ACCESS_DENIED_OR_NOT_CREATED = 0xE021;
	
	const ERROR_MESSAGES = [
		# 0xE000...
		self::ERR_NEED_DATA => self::MSG_NEED_DATA,
		self::ERR_NEED_USERNAME => 'Need username',
		self::ERR_NEED_PASSWORD => 'Need password',
		self::ERR_USERNAME_OR_PASSWORD_WRONG => 'Username or password are wrong',
		self::ERR_NEED_FIELD_VALUE => 'Need field "%s"',
		self::ERR_WRONG_FIELD_VALUE => 'Wrong value for field "%s"',
		# 0xE010...
		self::ERR_OBJECT_NOT_SAVED => 'Object not saved with message "%s"',
		# 0xE020...
		self::ERR_TASK_ACCESS_DENIED_OR_NOT_CREATED => 'Task is not create or you have no access to task',
	];
	
	const MSG_NEED_DATA = 'Need data';
	const MSG_FORBIDDEN = 'User or password are wrong';
	const MSG_USER_LOCKED = 'User is locked';
	
	/*
	 * Метод генерирует ошибку прямо в коде
	 */
	static public function e($code, ...$args)
	{
		throw new \Exception(sprintf(self::ERROR_MESSAGES[$code], ...$args), $code);
	}
	
	/*
	 * Метод генерирует debug-лог
	 */
	static public function d($class, $action, $message)
	{
		//...
	}
	
	public function saveException($e)
	{
		$sql = "INSERT INTO arc_exception (id_user, class, code, file, line, message, trace) VALUES(:id_user, :class, :code, :file, :line, :message, :trace)";
		$query = $this->db->execute($sql, [
			'id_user' => null,
			'class' => get_class($e),
			'code' => $e->getCode(),
			'file' => $e->getFile(),
			'line' => $e->getLine(),
			'message' => $e->getMessage(),
			'trace' => json_encode($e->getTrace()),
		]);
	}
	
	public function saveLog($type = 'debug', $class, $action, $message)
	{
		$sql = "INSERT INTO arc_log (type, id_user, class, action, message) VALUES(?, ?, ?, ?, ?)";
		$query = $this->db->execute($sql, [
			$type,
			null,
			$class,
			$action,
			$message
		]);
	}
}
<?php
define('LOAD_ACCESS', microtime(true));

# папка приложения
define('__WEB__', realpath(__DIR__) . DIRECTORY_SEPARATOR);
require_once(__WEB__ . '../backend/bootstrap.php');

require_once(__APP__ . 'api/Api.php');

/**
 * Выполняется что-то
 */
try
{
	$micro = new \Phalcon\Mvc\Micro($di);
	(new Api($micro))->handle();
}
catch (\Throwable $e)
{
	// что-то случилось, и ошибка не обработана выше
		
	// пробую сохранить ошибку в БД
	// если не получится, значит БД не настроена
	// ошибки в файлы писать не буду
	$di['errors']->saveException($e);
	
	// просто вывожу текст ошибки в стек
	sendResponse(500, (object)[
		'class' => get_class($e),
		'code' => $e->getCode(),
		'message' =>  $e->getMessage(),
		'file' => $e->getFile(),
		'line' => $e->getLine(),
		'trace' => $e->getTrace(),
	]);
}

function sendResponse($code, $json)
{
	(new \Phalcon\Http\Response())
		->setStatusCode($code)
		->setJsonContent($json)
		->send();
}
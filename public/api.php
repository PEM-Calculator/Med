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
	(new Api($di))->handle();
}
catch (\Throwable $e)
{
	// что-то случилось, и ошибка не обработана выше
	// просто вывожу текст ошибки с стек
	sendResponse(500, (object)[
		'code' => $e->getCode(),
		'error' => get_class($e) . ': ' . $e->getMessage(),
		'line' => $e->getFile() . ':' . $e->getLine(),
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
<?php
defined('LOAD_ACCESS') || die('Have no access');

use \Phalcon\DI\FactoryDefault;
//use \Phalcon\Events\Manager as EventsManager;
//use \Phalcon\Flash\Session as FlashSession;
//use \Phalcon\Mvc\{View,Dispatcher};
//use \Phalcon\Mvc\Url as UrlProvider;
//use \Phalcon\Mvc\View\Engine\Volt as VoltEngine;
//use \Phalcon\Mvc\Model\Metadata\Memory as MetaData;
//use \Phalcon\Session\Adapter\Files as SessionAdapter;

$di = new FactoryDefault();

$di->set('config', $config);

$di->set('db', function() use ($config) {
	$dbclass = '\Phalcon\Db\Adapter\Pdo\\' . $config->database->adapter;
	return new $dbclass([
		'host'     => $config->database->host,
		'port'     => $config->database->port,
		'username' => $config->database->username,
		'password' => $config->database->password,
		'dbname'   => $config->database->dbname,
		'charset'  => $config->database->charset,
	]);
}, true);

$di->set('session', function() {
	//$session = new Redis(...)
	$session = new \Phalcon\Session\Adapter\Files();
	$session->start();
	return $session;
});

$di['cookies'] = function() {
	$cookies = new \Phalcon\Http\Response\Cookies();
	$cookies->useEncryption(false);
	return $cookies;
};

$di['response'] = function() {
	return new \Phalcon\Http\Response();
};

$di['request'] = function() {
	return new \Phalcon\Http\Request();
};

$di['errors'] = function() {
	return new \Med\Libraries\Errors();
};
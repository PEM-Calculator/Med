<?php
defined('LOAD_ACCESS') || die('Access denied');

define('__ROOT__', realpath(__DIR__ . '/..') . DIRECTORY_SEPARATOR);
define('__APP__', realpath(__DIR__) . DIRECTORY_SEPARATOR);

use \Phalcon\Config\Adapter\Ini as ConfigIni;

# Загружаю конфиг
$config = new ConfigIni(__APP__ . 'config.ini');

# Настройка локали
mb_regex_encoding($config->application->regex_encoding);
setlocale(LC_ALL, $config->application->locale);

# Дефайню константы из конфига
if(isset($config->const)) {
	foreach($config->const as $key => $value) {
		define($key, $value);
	}
}

# Запуск приложения
require __APP__ . 'config/loader.php';
require __APP__ . 'config/services.php';
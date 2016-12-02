<?php
defined('LOAD_ACCESS') || die('Access denied');

$loader = new \Phalcon\Loader();

$loader->registerDirs([
	__APP__ . $config->application->controllersDir,
	__APP__ . $config->application->pluginsDir,
	__APP__ . $config->application->libraryDir,
	__APP__ . $config->application->modelsDir,
	__APP__ . $config->application->formsDir,
])->register();

$loader->registerNamespaces([
	'Med\Models' => __APP__ . 'Med' . DIRECTORY_SEPARATOR . 'models',
	'Med\Forms' => __APP__ . 'Med' . DIRECTORY_SEPARATOR . 'forms',
	'Med\Behaviors' => __APP__ . 'Med' . DIRECTORY_SEPARATOR . 'behaviors',
	'Med\Libraries' => __APP__ . 'Med' . DIRECTORY_SEPARATOR . 'libraries',
])->register();
<?php
defined('LOAD_ACCESS') || die('Access denied');

use \Med\Libraries\{
	Errors, UserAuth
};
use \Med\Models\{
	User, UserLogin
};

class Api
{
	/** @var \Phalcon\DiInterface */
	private $di;
	/** @var \Phalcon\Mvc\Micro */
	private $micro;

	function __construct(\Phalcon\Mvc\Micro $micro)
	{
		$this->di = $micro->getDi();

		$micro->notFound([$this, 'notFound']);
		$micro->error([$this, 'error']);

		// Методы авторизации
		$micro->post('/signup', [$this, 'signUp']);         // v 0.1
		$micro->post('/login', [$this, 'logIn']);           // v 0.1
		$micro->get('/logout', [$this, 'logOut']);          // v 0.1
		$micro->get('/loginfo', [$this, 'logInfo']);        // v 0.1

		// Методы работы с задачами
		$micro->post('/task.add', [$this, 'addTask']);
		$micro->get('/task.get/{id:[0-9]+}', [$this, 'getTask']);
		$micro->get('/task.list', [$this, 'getTaskList']);
		$micro->post('/task.edit/{id:[0-9]+}', [$this, 'editTask']);
		$micro->post('/task.remove/{id:[0-9]+}', [$this, 'removeTask']);

		$micro->get('/hello/{name}', [$this, 'sayHello']);

		$this->micro = $micro;
	}

	//---------------------------------
	//	Методы авторизации
	//---------------------------------

	/**
	 * Регистрация
	 */
	function signUp()
	{
		$post = $this->di->getRequest()->getPost();

		$data = json_decode($post['data'] ?? '', true);

		if (!is_array($data)) {
			$this->setResponseError(400, Errors::MSG_NEED_DATA);
			return;
		}

		$user = new \Med\Models\User();
		$user->assign($data);

		if (!$user->save())
			Errors::e(Errors::ERR_OBJECT_NOT_SAVED, $user->getMessages()[0]);

		// все OK
		$this->setResponseOK();
	}

	/**
	 * Авторизация
	 */
	function logIn()
	{
		$post = $this->di->getRequest()->getPost();
		$session = $this->di->getSession();

		// пользователь пытается авторизоваться

		// сначала чищу сессию
		$session->set('auth', null);

		$username = $post['username'] ?? null;
		$password = $post['password'] ?? null;

		$user = null;
		if ($username && $password) {
			$user = \Med\Models\User::findFirstByField('username', $username);
		}

		if (!$user || !$user->checkPassword($password))
			// пользователь не найден или пароль не совпал
			$this->setResponseError(403, Errors::MSG_FORBIDDEN);
		else {
			if ($user->isLocked())
				// пользователь заблокирован
				$this->setResponseError(403, Errors::MSG_USER_LOCKED);
			else {
				// удачно
				// авторизую пользователя
				$userLogin = $user->login();
				// сообщаю hash и time_live
				$this->setResponseOK([
					'userhash' => $userLogin->hash,
					'timelive' => gmdate('c', $userLogin->time_live),
				]);
			}
		}
	}

	/**
	 * Выход
	 */
	function logOut()
	{
		if (!$this->checkAuth()) return;

		UserAuth::logout();
		$this->di->getSession()->set('auth', null);

		$this->setResponseOK();
	}

	/**
	 * Детализация авторизации по сессии или хеш-ключу
	 */
	function logInfo()
	{
		if (!$this->checkAuth()) return;

		$this->setResponseOK([
			'timelive' => gmdate('c', UserAuth::$userLogin->time_live)
		]);
	}

	//---------------------------------
	//  Методы работы с проектами
	//---------------------------------

	/**
	 * Добавляет новый проект
	 */
	function addTask()
	{
		if (!$this->checkAuth()) return;

		$post = $this->di->getRequest()->getPost();
		$data = json_decode($post['data'] ?? '', true);

		if (!is_array($data)) {
			$this->setResponseError(400, Errors::MSG_NEED_DATA);
			return;
		}

		$task = new \Med\Models\Task();
		try {
			$task->assign($data);
		} catch (\Exception $ex) {
			$this->setResponseError(400, $ex->getMessage());
			return;
		}

		if (!$task->save())
			Errors::e(Errors::ERR_OBJECT_NOT_SAVED, $task->getMessages()[0]);

		// все OK
		$this->setResponseOK([
			'id' => $task->getId()
		]);
	}

	function getTask(int $taskId)
	{

	}

	function getTaskList()
	{

	}

	function editTask(int $taskId)
	{

	}

	function removeTask(int $taskId)
	{

	}

	//---------------------------------
	//	Дежурные методы
	//---------------------------------

	/**
	 * Вызывает handle() для приложения и отправляет ответ
	 */
	function handle()
	{
		$this->micro->handle();
		$this->di->getResponse()->send();
	}

	/**
	 * Проверяет авторизацию
	 */
	private function checkAuth()
	{
		/** @var \Phalcon\Http\Request $request */
		$request = $this->di->getRequest();
		/** @var \Phalcon\Session\Adapter\Files $session */
		$session = $this->di->getSession();

		$hash = $request->get('userhash') ?? $session->auth ?? null;
		// хеш не задан
		if (!$hash) {
			$this->setResponseError(401, 'Unauthorized');
			return false;
		}

		// хеш есть, ищу и проверяю UserLogin
		$userLogin = UserLogin::findFirstByField('hash', $hash);

		if (!$userLogin || $userLogin->time_logout) {
			$this->setResponseError(401, 'Unauthorized');
			return false;
		}

		if ($userLogin->time_live < time()) {
			$this->setResponseError(401, 'Time live expired');
			return false;
		}

		// UserLogin ok

		// проверяю юзера

		$user = User::findFirst($userLogin->id_user);

		if (!$user) {
			$this->setResponseError(401, 'User is not found');
			return false;
		}

		if ($user->isLocked()) {
			// пользователь заблокирован
			$this->setResponseError(403, Errors::MSG_USER_LOCKED);
			return false;
		}

		// User ok
		UserAuth::set($user, $userLogin);

		return true;
	}

	/**
	 * Создает положительный ответ
	 */
	function setResponseOK($data = NULL)
	{
		if (!$data)
			$data = [];

		$data['status'] = 'OK';

		$this->di->getResponse()
			->setJsonContent((object)$data);
	}

	/**
	 * Создает ответ с ошибкой
	 * @param $statusCode
	 * @param $error
	 * @param null $data
	 */
	function setResponseError(int $statusCode, string $error, array $data = NULL)
	{
		if (!$data)
			$data = [];

		$data['error'] = $error;

		$this->di->getResponse()
			->setStatusCode($statusCode)
			->setJsonContent((object)$data);
	}

	function notFound()
	{
		$this->setResponseError(400, 'Method not found');
	}

	function error($e)
	{
		$this->di->getResponse()
			->setStatusCode(500)
			->setJsonContent((object)[
				'error' => 'SOMETHING WENTS WRONG -- EMAE',
				'code' => $e->getCode(),
			]);
	}
}
<?php
defined('LOAD_ACCESS') || die('Access denied');

use \Med\Libraries\Errors;

class Api
{
	private $di, $micro;
	
	function __construct($di)
	{
		$this->di = $di;
		$micro = new \Phalcon\Mvc\Micro($di);
		
		$micro->notFound([$this, 'notFound']);
		$micro->error([$this, 'error']);
		
		$micro->post('/signup', [$this, 'signUp']);	// v 0.1
		$micro->get('/signup', [$this, 'signUp']);	// v 0.1
		$micro->post('/signin', [$this, 'signIn']);	// v 0.1
		
		$micro->get('/hello/{name}', [$this, 'sayHello']);
		
		$this->micro = $micro;
	}
	
	function handle()
	{
		$this->micro->handle();
		$this->di->getResponse()->send();
	}
	
	private function checkAuth()
	{
		$session = $this->di->getSession();
		
		if(!isset($session->auth)) {
			$this->di->getResponse()
				->setStatusCode(403)
				->setJsonContent((object)[
					'error' => 'Unathorized',
				]);
		}
	}
	
	//-----------------------------------------
	
	function signUp()
	{
		$post = $this->di->getRequest()->getPost();
		if(!isset($post['data']))
			Errors::e(Errors::ERR_NEED_DATA);
			
		$user = new \Med\Models\User();
		$user->assign(json_decode($post['data'], true));
		
		if(!$user->save())
			Errors::e(Errors::ERR_OBJECT_NOT_SAVED, $user->getMessages()[0]);
		
		// все OK
		$this->di->getResponse()
			->setJsonContent((object)[
				'status' => 'OK',
				'id' => $user->getId(),
				'user' => $user,
			]);
	}
	
	function signIn()
	{
		
	}
	
	function sayHello($name)
	{
		if(!$this->checkAuth()) return;
		
		$this->di->getResponse()->setContent("Hello, {$name}");
	}
	
	//-----------------------------------------
	
	function notFound()
	{
		$this->di->getResponse()
			->setStatusCode(404)
			->setJsonContent((object)[
				'error' => 'Method not found',
			]);
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
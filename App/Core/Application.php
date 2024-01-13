<?php

namespace App\Core;

/**
 * Class Application
 */

class Application
{

	public static string $ROOT_DIR;
	public static Application $app;

	public Database $db;
	public Router $router;
	public Request $request;
	public Response $response;

	/**
	 * Application contructor
	 * 
	 * @param string|object $rootPath
	 */

	public function __construct($rootPath, $config = []){

		self::$ROOT_DIR = $rootPath;
		self::$app = $this;

		try{
			$this->db = new Database($config['database'] ?? false);
		} catch (\Exception $e) {
			echo $e->getMessage();
			exit;
		}

		$this->request = new Request();
		$this->response = new Response();
		$this->router = new Router($this->request, $this->response);
	}

	/**
	 * Handle application
	 */

	public function run(){
		try {
			echo $this->router->resolve();
		} catch (\Exception $e) {
			echo $e->getMessage();
		}
	}
	
}
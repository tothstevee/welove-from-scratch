<?php

namespace App\Core;

/**
 * Class Router
 */
class Router
{
	public Request $request;
	public Response $response;

	protected array $routes = [];

	/**
	 * Router contructor
	 * 
	 * @param Request $request
	 * @param Response $response 
	 */

	public function __construct(Request $request, Response $response){
		$this->request = $request;
		$this->response = $response;
	}

	/**
	 * Define GET request handler
	 * 
	 * @return void
	 */

	public function get($path, $callback){
		$this->routes['get'][$path] = $callback;
	}

	/**
	 * Define POST request handler
	 * 
	 * @return void
	 */

	public function post($path, $callback){
		$this->routes['post'][$path] = $callback;
	}

	/**
	 * Resolve request by routes
	 * 
	 * @return any
	 */

	public function resolve(){
		$path = $this->request->getPath();
		$method = $this->request->getMethod();
		$callback = $this->routes[$method][$path] ?? false;

		if($callback === false){
			throw new \App\Exception\NotFoundException;
		}

		if(is_array($callback)){
			$callback[0] = new $callback[0]($this->request,$this->response);

			return call_user_func($callback, [$this->request]);
		}

		if(is_string($callback)){
			return $this->response->view($callback);
		}

		return call_user_func($callback);
	}
}
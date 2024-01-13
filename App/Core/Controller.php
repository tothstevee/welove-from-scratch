<?php

namespace App\Core;

/**
 * Class Database
 */
class Controller
{

	public Request $request;
	public Response $response;

    /**
     * Controller contructor
     * 
     * @param Request $request
     * @param Response $response
     */

	public function __construct(Request $request, Response $response)
    {
    	$this->request = $request;
    	$this->response = $response;
    }

    /**
     * Handle views
     * 
     * @param string $view
     * @param array<any> $params
     */

    public function view($view, $params = []){
    	return $this->response->view($view, $params);
    }
}
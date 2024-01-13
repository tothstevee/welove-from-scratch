<?php

namespace App\Core;

/**
 * Class Response
 */
class Response
{

	/**
	 * Set response http status code
	 * 
	 * @param intieger $code
	 */

	public function abort($code = 200){
		switch ($code) {
			case '404':
				throw new \App\Exception\NotFoundException;
				break;
			
			default:
				http_response_code($code);
				break;
		}
		exit;
	}

	/**
	 * Returning json
	 * 
	 * @return json
	 */

	public function json($content){
		header("Content-Type: application/json");
		echo json_encode($content);
	}


	/**
	 * Compose the view
	 * 
	 * @return string
	 */

	public function view($view, $params = []){
		$layoutCotnent = $this->getLayoutContent($view);
		$viewContent = $this->getViewContent($view ,$params);

		return str_replace("{{content}}", $viewContent, $layoutCotnent);
	}


		/**
		 * Get the content of layout
		 * 
		 * @return string
		 */

		protected function getLayoutContent($view){
			ob_start();
			$viewName = $view;
			include_once Application::$ROOT_DIR."/views/layouts/base.php";
			return ob_get_clean();
		}

		/**
		 * Get the content of view
		 * 
		 * @return string
		 */

		protected function getViewContent($view ,$params = []){
			$path = Application::$ROOT_DIR."/views/pages/".$view.".php";

			if(!file_exists($path)){
				throw new \App\Exception\NotFoundException;
			}

			foreach ($params as $key => $value) {
	            $$key = $value;
	        }

			ob_start();
			include_once $path;
			return ob_get_clean();
		}
}
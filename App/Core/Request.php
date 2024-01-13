<?php

namespace App\Core;

/**
 * Class Request
 */
class Request
{

	/**
	 * Get the request path
	 * 
	 * @return string
	 */

	public function getPath(){
		$path = $_SERVER['REQUEST_URI'] ?? "/";
		$questionmark_position = strpos($path, "?");

		if($questionmark_position === false){
			return $path;
		}

		return substr($path, 0, $questionmark_position);
	}

	/**
	 * Get the request method
	 * 
	 * @return string<get|post|...>
	 */

	public function getMethod(){
		return strtolower($_SERVER['REQUEST_METHOD']);
	}

	/**
	 * Get the request body
	 * 
	 * @return array<string>
	 */

	public function getBody()
    {
        $data = [];
        if ($this->getMethod() == "get") {
            foreach ($_GET as $key => $value) {
                $data[$key] = filter_input(INPUT_GET, $key, FILTER_SANITIZE_SPECIAL_CHARS);
            }
        }
        if ($this->getMethod() == "post") {
            foreach ($_POST as $key => $value) {
                $data[$key] = filter_input(INPUT_POST, $key, FILTER_SANITIZE_SPECIAL_CHARS);
            }
        }
        return $data;
    }

    /**
     * Request containes input or param
     * 
     * @param string $field
     * @return boolean
     */

    public function has($field){

    	if(is_array($field)){
    		foreach ($field as $key => $value) {
    			if(!isset($this->getBody()[$value])){
    				return false;
    			}
    		}

    		return true;
    	}

    	return isset($this->getBody()[$field]);
    }

    /**
     * Get the request param or input
     * 
     * @param string $field
     * @return string|boolean
     */

    public function get($field){
    	if(!$this->has($field)){
    		return false;
    	}

    	return $this->getBody()[$field];
    }

    /**
     * Validate typical input cases
     * 
     * @param array $mapping
     * @return boolean
     */

    public function validate($mapping = []){
    	foreach ($mapping as $key => $input) {
    		$params = explode(",", $input);
    		if(!$this->validateFiled($this->get($params[0]), $params[1])){
    			return false;
    		}
    	}

    	return true;
    }

    	private function validateFiled($value, $type){
    		switch ($type) {
	    		case 'email':
	    			return filter_var($value, FILTER_VALIDATE_EMAIL);
	    			break;

	    		case 'status':
	    			return \App\Model\Status::find($value);
	    			break;
	    		
	    		default:
	    			return true;
	    			break;
	    	}
    	}
}
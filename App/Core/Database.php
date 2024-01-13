<?php

namespace App\Core;

/**
 * Class Database
 */
class Database
{

	public \PDO $connection;

	/**
	 * Database contructor
	 * 
	 * @param string|object $rootPath
	 */

	public function __construct($config = [])
    {
    	if(!$config){
    		throw new \Exception("No database configured", 1);
    	}

    	$this->connection = new \PDO("mysql:host=".$config['host'].";dbname=".$config['database'].";port=".$config['port'] ?? "3306", $config['username'], $config['password']);
        $this->connection->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        $this->connection->setAttribute(\PDO::ATTR_EMULATE_PREPARES,false);
    }

    /**
     * Destroy sql connection
     */

    public function __destruct(){
        unset($this->connection);
    }

    /**
     * Prepare
     * 
     * @param string $sql
     * @return PDOStatement
     */

    public function prepare($sql): \PDOStatement
    {
        return $this->connection->prepare($sql);
    }
}
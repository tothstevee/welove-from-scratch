<?php

namespace App\Core;

class Model
{
	public static string $table;
	public static string $primaryKey = "id";

	/**
     * Get the list of the records
     * 
     * @param string $query
     * @param array<any> $attr
     * @return array<Object>
     */
	
	public static function get($query = false, $attr = []){
		$tableName = static::$table;

		if(!$query){
			$query = "SELECT * FROM $tableName";
		}

		$statement = static::prepare($query);

		foreach ($attr as $key => $value) {
			$statement->bindValue(":".$key, $value);
		}

		try {
			$statement->execute();
			return $statement->fetchAll(\PDO::FETCH_OBJ);
		} catch (\Exception $e) {
			return false;
		}
		
	}

	/**
     * Find record by primary key
     * 
     * @param integer $id
     * @param string $query
     * @return Object
     */

	public static function find($id, $query = false){
		$tableName = static::$table;
		$primaryKey = static::$primaryKey;

		if(!$query){
			$query = "SELECT * FROM $tableName";
		}

		$statement = self::prepare($query." WHERE $primaryKey = :id");
		$statement->bindValue(':id', $id);

		try {
			$statement->execute();
			return $statement->fetchObject();
		} catch (\Exception $e) {
			return false;
		}
	}

	/**
     * Find record by key
     * 
     * @param string $field
     * @param string $value
     * @param string $query
     * @return Object
     */

	public static function findBy($field, $value){
		$tableName = static::$table;

		$statement = self::prepare("SELECT * FROM $tableName WHERE $field = :value");
		$statement->bindValue(':value', $value);

		try {
			$statement->execute();
			return $statement->fetchObject();
		} catch (\Exception $e) {
			return false;
		}
	}

	/**
     * Create record
     * 
     * @param sql $id
     * @return PDO Statement
     */

	public static function create($values){
		$tableName = static::$table;

		$attributes = array_keys($values);

		$query = "INSERT INTO $tableName (" . implode(",", $attributes) . ") 
                VALUES (:" . implode(",:", $attributes) . ")";

		$statement = self::prepare($query);

		try {
			$statement->execute($values);
			return Application::$app->db->connection->lastInsertId();
		} catch (\Exception $e) {
			return false;
		}
	}

	/**
     * Update record
     * 
     * @param sql $id
     * @return PDO Statement
     */

	public static function update($values, $id, $field = false){
		if(!$field){
			$field = static::$primaryKey;
		}

		$tableName = static::$table;
		$primaryKey = static::$primaryKey;

		$attributes = array_keys($values);

		$update = [];
		foreach ($values as $key => $value) {
			$update[] = implode(" = :",[$key, $key]);
		}

		$statement = self::prepare("UPDATE $tableName SET ".implode(",",$update)." WHERE $field = :id");
		
		$values['id'] = $id;
		
		try {
			$statement->execute($values);
			return true;
		} catch (\Exception $e) {
			return false;
		}
	}

	/**
     * Delete record
     * 
     * @param integer|string $value
     * @param integer|string|boolean $field
     * @return boolean
     */

	public static function delete($value, $field = false){
		$tableName = static::$table;
		$primaryKey = static::$primaryKey;

		if(!$field){
			$field = static::$primaryKey;
		}

		$statement = self::prepare("DELETE FROM $tableName WHERE $field = :value");
		$statement->bindValue(":value", $value);

		try {
			$statement->execute();
			return true;
		} catch (\Exception $e) {
			return false;
		}
	}

	/**
     * Make pdo statement
     * 
     * @param sql $id
     * @return PDO Statement
     */

	public static function prepare($sql) {
		return Application::$app->db->prepare($sql, [\PDO::ATTR_CURSOR => \PDO::CURSOR_FWDONLY]);
	}
}
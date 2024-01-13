<?php

namespace App\Model;

use App\Core\Model;

class Project extends Model
{
	public static string $table = "projects";

	private static string $selectQuery = "SELECT projects.*,project_owner_pivot.owner_id as owner_id,project_status_pivot.status_id as status_id 
			FROM projects 
			LEFT JOIN project_owner_pivot ON project_owner_pivot.project_id = projects.id
			LEFT JOIN project_status_pivot ON project_status_pivot.project_id = projects.id";

	/**
     * Get item with all relations
     * 
     * @param integer $id
     * @return Object|Boolean
     */

	public static function findWithRelation($id){
		$original = parent::find($id, self::$selectQuery);

		if(!$original) return false;

		$original->owner = Owner::find($original->owner_id);
		$original->status = Status::find($original->status_id);

		return $original;
	}

	/**
     * Get list with relations
     * 
     * @return array<Object>|Boolean
     */

	public static function getWithRelations(){
		$original = parent::get(self::$selectQuery);

		if(!$original) return false;

		$cache = [];

		foreach ($original as $key => $o) {
			$o->owner = isset($cache['owner'][$o->owner_id]) ? $cache['owner'][$o->owner_id] : false;

			if(!$o->owner){
				$o->owner = Owner::find($o->owner_id);
				$cache['owner'][$o->owner_id] = $o->owner;
			}


			$o->status = isset($cache['status'][$o->status_id]) ? $cache['status'][$o->status_id] : false;

			if(!$o->status){
				$o->status = Status::find($o->status_id);
				$cache['status'][$o->status_id] = $o->status;
			}
		}

		return $original;
	}

	public static function deleteWithRelations($id){
		$owner = ProjectOwner::delete($id, 'project_id');
		$status = ProjectStatus::delete($id, 'project_id');
		$self = parent::delete($id);

		return $self && $owner && $status;
	}
}
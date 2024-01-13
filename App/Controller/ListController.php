<?php

namespace App\Controller;

use App\Core\Controller;
use App\Model\Project;

class ListController extends Controller
{
	public function render(){
		$projects = Project::getWithRelations();

		return $this->view('list', ['projects' => $projects]);
	} 
}
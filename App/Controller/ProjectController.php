<?php

namespace App\Controller;

use App\Core\Controller;
use App\Model\Project;
use App\Model\Status;
use App\Model\Owner;
use App\Model\ProjectOwner;
use App\Model\ProjectStatus;

class ProjectController extends Controller
{   
    /**
     * Handle the GET request
     *
     * @return View
     */

	public function render(){
		$statuses = Status::get();
		$params = $this->extendParamsByRequest(['statuses' => $statuses]);

		return $this->view('project',$params);
	} 

		private function extendParamsByRequest($current = []){
            if(!$this->request->has('projectId')){
                return $current;
            }

            $project = Project::findWithRelation($this->request->get('projectId'));

            if(empty($project)){
            	return $this->response->abort(404);
            }

            return array_merge($current, ['project' => $project]);
        }

    /**
     * Handle the POST request for save / update
     *
     * @return JSON
     */

    public function save(){
        if(
            !$this->request->has(['owner_email','owner_name','status','description','title'])
            || !$this->request->validate(['owner_email,email','status,status'])
        ){
            return $this->response->json(['success' => false, 'message' => 'validation']);
        }

        $project = Project::find($this->request->get('project_id'));

        if(!$project){
            $project_id = $this->storeNew();
            return $this->response->json(['success' => true, 'action' => '/project?projectId='.$project_id]);
        }

        $owner = $this->getOwnerByRequest();

        ProjectStatus::update(['status_id' => $this->request->get('status')],
            $project->id,
            'project_id'
        );

        ProjectOwner::update(['owner_id' => $owner],
            $project->id,
            'project_id'
        );

        Project::update([
            'title' => $this->request->get('title'),
            'description' => $this->request->get('description')
        ], $project->id);

        return $this->response->json(['success' => true, 'action' => '/']);
    }

        private function storeNew(){
            $project_id = Project::create([
                'title' => $this->request->get('title'),
                'description' => $this->request->get('description')
            ]);

            $owner = $this->getOwnerByRequest();

            ProjectStatus::create([
                'project_id' => $project_id,
                'status_id' => $this->request->get('status')
            ]);

            ProjectOwner::create([
                'project_id' => $project_id,
                'owner_id' => $owner
            ]);

            return $project_id;
        }

        private function getOwnerByRequest(){
            $owner = Owner::findBy('email', $this->request->get('owner_email'));

            if(!$owner){
                $owner_id = Owner::create([
                    'email' => $this->request->get('owner_email'),
                    'name' => $this->request->get('owner_name')
                ]);

                return $owner_id;
            }

            return $owner->id;
        }

    /**
     * Handle the POST request for delete
     *
     * @return JSON
     */

    public function delete(){
        if(!$this->request->has('projectId')){
            return $this->response->json(['success' => false]);
        }

        $project = Project::find($this->request->get('projectId'));

        if(!$project){
            return $this->response->json(['success' => false]);
        }

        $delete = Project::deleteWithRelations($this->request->get('projectId'));

        return $this->response->json(['success' => $delete]);
    }
}
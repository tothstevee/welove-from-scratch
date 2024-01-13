<?php foreach ($projects as $key => $project): ?>
	<div class="row mb-4 js-projectRow" data-project="<?= $project->id ?>">
		<div class="col-12">
			<div class="card">
			  <div class="card-body">
			    <?php if (!empty($project->status)): ?>
			  	 <small style="float: right;" class="text-body-secondary"><?= $project->status->name ?></small>
			    <?php endif ?>

			  	<h5 class="card-title"><?= $project->title ?></h5>
			    <?php if (!empty($project->owner)): ?>
			      <h6 class="card-subtitle mb-2 text-body-secondary"><?= $project->owner->name ?> (<?= $project->owner->email ?>)</h6>  
			    <?php endif ?>
			    
			    <div class="btn-group mt-4">
			    	<a class="btn btn-primary" href="/project?projectId=<?= $project->id ?>">Szerkesztés</a>
			    	<a class="btn btn-danger js-deleteProject" data-project="<?= $project->id ?>">Törlés</a>
			    </div>
			  </div>
			</div>
		</div>
	</div>
<?php endforeach ?>
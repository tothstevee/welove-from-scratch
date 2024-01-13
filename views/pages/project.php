<div class="row">
	<div class="col-12">
		<form method="post" action="/project/save" class="js-form">
			<div class="mb-3">
			  <label for="title" class="form-label">Cím</label>
			  <input type="title" class="form-control" id="title" name="title" <?= !empty($project) ? 'value="'.$project->title.'"' : "" ?>>
			</div>

			<div class="mb-3">
			  <label for="description" class="form-label">Leírás</label>
			  <textarea class="form-control" id="description" name="description"><?= !empty($project) ? $project->description : "" ?></textarea>
			</div>

			<div class="mb-3">
			  <label for="status" class="form-label">Státusz</label>
			  <select class="form-control" name="status" id="status">
			  	<?php foreach ($statuses as $key => $status): ?>
			  		<option value="<?= $status->id ?>" <?= !empty($project) && $project->status->id == $status->id ? 'selected' : "" ?>><?= $status->name ?></option>
			  	<?php endforeach ?>
			  </select>
			</div>

			<div class="mb-3">
			  <label for="owner_name" class="form-label">Kapcsolattartó neve</label>
			  <input type="title" class="form-control" id="owner_name" name="owner_name" <?= !empty($project->owner) ? 'value="'.$project->owner->name.'"' : "" ?>>
			</div>

			<div class="mb-3">
			  <label for="owner_email" class="form-label">Kapcsolattartó email címe</label>
			  <input type="title" class="form-control" id="owner_email" name="owner_email" <?= !empty($project->owner) ? 'value="'.$project->owner->email.'"' : "" ?>>
			</div>

			<div class="mb-3">
				<?php if (!empty($project)): ?>
					<input type="hidden" name="project_id" value="<?= $project->id ?>">
				<?php endif ?>
				<button type="submit" class="btn btn-primary">Mentés</button>
			</div>
		</form>
	</div>
</div>
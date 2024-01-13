import http from '../core/http.js';

$(() => {
	$(".js-deleteProject").on('click',(e) => {
		e.preventDefault();

		const projectId = $(e.currentTarget).data('project');

		http.request("/project/delete", 'post', {projectId: projectId}).then((response) => {
			if(response && response.success){
				$(".js-projectRow[data-project='"+projectId+"']").hide();
				return;
			}else{
				alert("A törlés sikertelen");
				return;
			}
		})
	})
});
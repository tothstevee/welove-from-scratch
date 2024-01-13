import http from '../core/http.js';

$(() => {
	$(".js-form").submit((e) => {
		e.preventDefault();

		http.request("/project/save", 'post', $(e.target).serialize()).then((response) => {
			if(response && response.success && response.action){
				alert("A mentés sikeres");
				window.location.replace(response.action);
				return;
			}else{
				alert("A mentés sikertelen");
				return;
			}
		})

		return false;
	})
})
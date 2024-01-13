const http = {
	request: (url, method, data = {}) => {

		if(method.toUpperCase() == "get"){
			const params = new URLSearchParams(data);
			url+="?"+params.toString();
		}

		return new Promise((resolve, reject) => {
			$.ajax({
                url: url,
                type: method.toUpperCase(),
                data: data,
                cache: false,
                success: data => {
                    resolve(data);
                },
                error: error => {
                    resolve(false);
                }
            })
		})
	}
};

export default http;
let ApiService = function (){

    let baseUrl = ""

    let init = function (url) {
        this.baseUrl = url;
    };

    let get = function (url,token,id="") {
        let response;
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': token
            }});
        $.ajax(
            {
                url: `${url}/${id}`,
                type: 'GET',
                async: false,
                success: function (data){
                    response = {
                        "status":true,
                        "data":data
                    }
                },
                error: function (error){
                    const message = JSON.parse(error.responseText).errors;
                    response = {
                        "status":false,
                        "error":message
                    }
                }
            }
        )
        return response;
    };

    let post = function (url, body,token,id='') {
        let response;

        const url2 = id !== '' ?`${url}/${id}` : `${url}`;
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': token
            }});
        $.ajax(
            {
                url: url2,
                type: 'POST',
                data: body,
                async: false,
                success: function (data){
                    response = {
                        "status":true,
                        "data":data
                    }
                    },
                error: function (error){
                   const message = JSON.parse(error.responseText).errors;
                    response = {
                        "status":false,
                        "error":message
                    }
                   }
            }
        )
        return response;
    };

    let del = function (url,token,id) {
        let response;
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': token
            }});
        $.ajax(
            {
                url: `${url}/${id}`,
                type: 'DELETE',
                async: false,
                success: function (data){
                    response = {
                        "status":true,
                        "data":data
                    }
                },
                error: function (error){
                    const message = JSON.parse(error.responseText).errors;
                    response = {
                        "status":false,
                        "error":message
                    }
                }
            }
        )
        return response;
    };

    let put = function (url, body, id='') {
        let response;

        const url2 = id !== '' ?`${url}/${id}` : `${url}`;
        $.ajax(
            {
                url: url2,
                type: 'PUT',
                data: body,
                async: false,
                success: function (data){
                    response = {
                        "status":true,
                        "data":data
                    }
                },
                error: function (error){
                    const message = JSON.parse(error.responseText).errors;
                    response = {
                        "status":false,
                        "error":message
                    }
                }
            }
        )
        return response;
    };


    return {
        init: init,
        get: get,
        post: post,
        del: del,
        put: put
    };
} ();

export default ApiService;


















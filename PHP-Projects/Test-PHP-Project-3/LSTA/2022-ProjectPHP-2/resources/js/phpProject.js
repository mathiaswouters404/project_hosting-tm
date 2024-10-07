let PhpProject = (function () {


    /**
     * Show a Noty toast.
     * @param {object} obj
     * @param {string} [obj.type='success'] - background color ('success' | 'error '| 'info' | 'warning')
     * @param {string} [obj.text='...'] - text message
     */
    function toast(obj) {
        let toastObj = obj || {};   // if no object specified, create a new empty object
        new Noty({
            layout: 'topRight',
            timeout: 3000,
            modal: false,
            type: toastObj.type || 'success',   // if no type specified, use 'success'
            text: toastObj.text || '...',       // if no text specified, use '...'
        }).show();
    }

    function popUp(type, text, button, callback, modal = null) {
        let noty = new Noty({
            type: type,
            text: text,
            buttons: [
                Noty.button(button, `btn btn-success`, function () {
                    if (modal !== null) {
                        modal.modal('hide');
                    }

                    callback();

                    noty.close();
                }),
                Noty.button('Cancel', 'btn btn-secondary ml-2', function () {
                    noty.close();
                })
            ]
        });
        noty.show();
    }

    function buildErrorString(errors){
        let message = "<ul>"
        $.each(errors, function (key, value) {
            $.each(value, function(index, value)  {
                message += `<li>${value}</li>`
            })
        });
        message += "</ul>"
        return message;
    }

    function buildNotyObject(type,text){
        return {
            'type': type,
            'text': text
        }
    }

    function buildErrorNoty(response){
        return PhpProject.buildNotyObject('error',PhpProject.buildErrorString(response.error));
    }
    return {
        toast: toast,
        buildErrorString:buildErrorString,
        buildNotyObject: buildNotyObject,
        buildErrorNoty: buildErrorNoty,
        popUp: popUp
    };
})();

export default PhpProject;

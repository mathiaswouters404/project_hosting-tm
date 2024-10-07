const AddLog = (function() {
    let logId = null;
    let logTitle = null;
    let logEvent = null;
    let logDescription = null;
    let logDate = null;
    let logVisitor = null

    const loadTable = function() {
        $.getJSON('/log/queryLogs/' + ($('#searchLogs').val()))
            .done(function (data) {
                console.log('data', data);
                // Clear tbody tag so no data is shown.
                $('tbody').empty();
                // Loop over each item in the array.
                $.each(data, function (key, value) {
                    let tr = `<tr class="logs__table__row col-12 row no-gutters">
                                <td class="logs__table__patient col-4 col-lg-2">${value.patient.fullName}</td>
                                <td class="logs__table__title col-4 col-lg-2">${value.title}</td>
                                <td class="logs__table__preview col-4 col-lg-2">${value.description.length < 30 ? value.description : value.description.substring(0, 30) + "..."}</td>
                                <td class="logs__table__datum col-4 col-lg-2">${ value.date ? value.date : '<i class="far fa-times-circle text-danger"></i>'}</td>
                                <td class="logs__table__afspraak col-4 col-lg-2">
                                        <!-- If an event is found, put it in a link with the name. -->
                                    ${ value.event ?  '<a href="patient/event/' + value.event.id + '">' + value.event.name + '</a>' :
                                    // <!-- otherwise use the visitor name. -->
                                    value.visitor ? "bezoeker " + value.visitor :
                                    // <!-- no result is a no content icon -->
                                    '<i class="far fa-times-circle text-danger"></i>'}
                                </td>
                                <td class="logs__table__crud col-4 col-lg-2"
                                    data-id="${value.id}"
                                    data-event="${value.event ? value.event.name : null}"
                                    data-title="${value.title}"
                                    data-description="${value.description}"
                                    data-date="${value.date}"
                                    data-visitor="${value.visitor}">

                                    <div class="btn-group btn-group-lg">
                                        <button class="btn btn-outline-success edit-log"
                                        data-toggle="tooltip"
                                        title="${value.title} aanpassen">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="btn btn-outline-danger delete-log"
                                        data-toggle="tooltip"
                                        title="${value.title} verwijderen">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                        <button class="btn btn-outline-info info-log"
                                        data-toggle="tooltip"
                                        title="${value.title} info">
                                            <i class="fas fa-info-circle"></i>
                                        </button>
                                    </div>
                                </td>
                        </tr>`;
                    // Append the row into the table and re-loop.
                    $('tbody').append(tr);
                });
            })
            .fail(function (event) {
                console.log('error', event);
            })
    }

    const setModalItems = function(tag){
        logId = $(tag).closest('td').data('id');
        logTitle = $(tag).closest('td').data('title');
        logEvent = $(tag).closest('td').data('event');
        logDescription = $(tag).closest('td').data('description');
        logDate = $(tag).closest('td').data('date');
        logVisitor = $(tag).closest('td').data('visitor');
    }

    // reset the model to hide all value and make them editable.
    const clearModal = function() {
        // hide all from groups.
        $('.form-group').addClass('d-none');
        $('#modal-form').attr('action', "");
        // Hide the subtitle.
        $('.modal-subtitle').addClass('d-none');
        $('.modal-title').text("");
        $('#title').prop('readonly', false).val("");
        $('#description').prop('readonly', false).val("");
        $('#date').prop('readonly', false).val("");
        $('#visitor').prop('readonly', false).val("");
        $('#modal-submit').removeClass('d-none');
        $('#modal-form').removeClass('was-validated');
        logId = null;
        logTitle = null;
        logEvent = null;
        logDescription = null;
        logDate = null;
        logVisitor = null;
    }

    const infoLog = function(tag){
        setModalItems(tag)
        // Check if there is a value in the subtitle.
        if(logEvent != null){
            $('.modal-subtitle').removeClass('d-none').text(`${logTitle} hangt samen met: ${logEvent}`);
        }
        // Update the modal title.
        $('.modal-title').text(`log: ${logTitle}`);
        // Set the pre-filled content of the log "title" field and set the form-group to visible.
        $('#title').prop('readonly', true).val(logTitle).parent().removeClass('d-none');
        // Set the pre-filled content of the log "description" field and set the form-group to visible.
        $('#description').prop('readonly', true).val(logDescription).parent().removeClass('d-none');
        // Set the pre-filled content of the log "date" field and set the form-group to visible.
        if (logDate != null) {
            $('#date').prop('readonly', true).val(logDate).parent().removeClass('d-none');
        }
        if (logVisitor != null) {
            // Set the pre-filled content of the log "visitor" field and set the form-group to visible.
            $('#visitor').prop('readonly', true).val(logVisitor).parent().removeClass('d-none');
        }
        // Show the modal itself.
        $('#modal-log').modal('show');
        //$('#title').prop('readonly', true);
        $('#modal-submit').addClass('d-none');
        // Show the modal
        $('#modal-log').modal('show');
    }

    const editLog = function(tag) {
        setModalItems(tag);

        // Check if there is a value in the subtitle.
        if(logEvent != null){
            $('.modal-subtitle').removeClass('d-none').text(`${logTitle} is connected to: ${logEvent}`);
        }
        // Update the modal title.
        $('.modal-title').text(`Edit ${logTitle}`);
        // Route the from wil submit the details to.
        // $('#modal-form').attr('action', `/log/${logId}`);
        $('#modal-form').attr('action', `put`);
        // Set the method of the form.
        $('input[name="_method"]').val('put');
        // Set the pre-filled content of the log "title" field and set the form-group to visible.
        $('#title').val(logTitle).parent().removeClass('d-none');
        // Set the pre-filled content of the log "description" field and set the form-group to visible.
        $('#description').val(logDescription).parent().removeClass('d-none');
        // Set the pre-filled content of the log "date" field and set the form-group to visible.
        $('#date').val(logDate).parent().removeClass('d-none');
        // Set the pre-filled content of the log "visitor" field and set the form-group to visible.
        $('#visitor').val(logVisitor).parent().removeClass('d-none');
        // Show select field to connect the log to a patient.
        $('#patient').parent().removeClass('d-none');
        // Show the modal itself.
        $('#modal-log').modal('show');
    }

    const createLog = function() {
        // Update the modal title.
        $('.modal-title').text(`New log`);
        // Route the from wil submit the details to.
        $('#modal-form').attr('action', `post`);
        // Set the method of the form.
        $('input[name="_method"]').val('post');

        $('.modal-title').parent().removeClass('d-none');
        $('#title').parent().removeClass('d-none');
        $('#description').parent().removeClass('d-none');
        $('#date').parent().removeClass('d-none');
        $('#visitor').parent().removeClass('d-none');
        // Show select field to connect the log to a patient.
        $('#patient').parent().removeClass('d-none');
        $('#event_id').parent().removeClass('d-none');
        // Show the modal
        $('#modal-log').modal('show');
    }
    const deleteLog = function(tag, token) {
        setModalItems(tag);
        // Set some values for Noty
        let text = `<p>De log <b>${logTitle}</b> verwijderen?</p>`;
        let type = 'warning';
        let btnText = 'Log verwijderen';
        let btnClass = 'btn-success';
        // If event not null, overwrite values for Noty
        if (logEvent != null) {
            text += `<p>OPGELET: deze log hang aan het event: ${logEvent}!</p>`;
            btnClass = 'btn-danger';
            type = 'error';
        }
        // Show Confirm Dialog
        let modal = new Noty({
            type: type,
            text: text,
            buttons: [
                Noty.button(btnText, `btn ${btnClass}`, function () {
                    // Delete genre and close modal
                    deleteFunction(logId, token);
                    loadTable()
                    modal.close();
                }),
                Noty.button('Annuleren', 'btn btn-secondary ml-2', function () {
                    modal.close();
                })
            ]
        }).show();
    }

    // Delete the log (the csrf-token must be generated on the html page).
    const deleteFunction = function(id, token) {
        // Delete the log from the database
        return ApiService.del('/log',token, id);
    }

    const submit = function (tag){
        if(ClientSideValidation.validateStatus("modal-form")){
            submitLog(tag);
        }
    }

    const _hideModal = function (){
        $('#modal-log').modal('hide');
        clearModal();
        loadTable();
    };

    const _postCreate = function (body, token) {
        return ApiService.post('/log',body,token);
    };

    const _putCreate = function (body) {
        return ApiService.put('/log',body, logId);
    };


    const submitLog = function(tag) {
        // Get the action property (the URL to submit)
        const action = $(tag).attr('action');
        // Serialize the form and send it as a parameter with the post
        const body = $(tag).serialize();
        console.log(action)

        if(action === 'put'){
            const response = _putCreate(body);
            Validator.validate(response, _hideModal);
            return Validator.validateStatus(response);
        }
        if(action === "post"){
            const response = _postCreate(body,"{{ csrf_token() }}");
            Validator.validate(response, _hideModal);
            return Validator.validateStatus(response);
        }
    }

    return {
        submit:submit,
        infoLog:infoLog,
        createLog:createLog,
        editLog:editLog,
        clearModal:clearModal,
        deleteLog:deleteLog,
        loadTable:loadTable
    }
})()

export default AddLog;

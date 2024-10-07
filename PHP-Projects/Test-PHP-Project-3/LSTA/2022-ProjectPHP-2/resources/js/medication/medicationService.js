const MedicationService = function () {

    let token;
    let filter = '';

    const init = function (crsf_token) {
        token = crsf_token;
        loadTable()
    };

    const setFilter = function(filterN) {
        filter = filterN;
        loadTable();
    };

    const prescribeFunction = function(tag) {
        // Get data attributes from td tag
        const id = tag.closest('td').data('id');
        const name = tag.closest('td').data('name');
        $('.modal-title').text(name + ' voorschrijven');
        $('form').attr('action', '/prescription');
        $('input[name="_method"]').val('post');
        $('#medication').val(id);
        const response = ApiService.get('patients/queryPatients', '{{csrf_token()}}');
        if(response.status == true) {
            $('#patient').empty();
            $.each(response.data, function(key, value) {
                let option = `<option value="${value.id}">${value.firstName} ${value.lastName}</option>`;
                $('#patient').append(option);
            });
        }
        $('#modal-prescription').modal('show');
    };

    const deleteFunction = function(tag) {
        // Get data attributes from td tag
        const id = tag.closest('td').data('id');
        const name = tag.closest('td').data('name');
        const patients = tag.closest('td').data('patients');
        // Set some values for Noty
        let text = `<p>Verwijder de medicatie <b>${name}</b>?</p>`;
        let type = 'warning';
        let btnText = 'Verwijder medicatie';
        let btnClass = 'btn-success';
        // If patients not 0, overwrite values for Noty
        if (patients > 0) {
            text += `<p>AANDACHT: Deze medicatie wordt gebruikt door ${patients} patiënt ${patients > 1 ? 'en' : ''}</p>`;
            btnText = `Verwijder medicatie`;
            btnClass = 'btn-danger';
            type = 'error';
        }
        // Show Confirm Dialog
        let modal = new Noty({
            type: type,
            text: text,
            buttons: [
                Noty.button(btnText, `btn ${btnClass}`, function () {
                    // Delete medication and close modal
                    deleteMedication(id);
                    modal.close();
                }),
                Noty.button('Annuleren', 'btn btn-secondary ml-2', function () {
                    modal.close();
                })
            ]
        }).show();
    };

    const submitPrescription = function (form) {
        // Get the action property (the URL to submit)
        const action = form.attr('action');
        // Serialize the form and send it as a parameter with the post
        const pars = form.serialize();
        console.log(pars);
        // Post the data to the URL
        $.post(action, pars, 'json')
            .done(function (data) {

                // show success message
                PhpProject.toast({
                    type: data.type,
                    text: data.text
                });
                console.log(data.prescription);
                // Hide the modal
                $('#modal-prescription').modal('hide');

            })
            .fail(function (e) {
                console.log('error', e);
                // e.responseJSON.errors contains an array of all the validation errors
                console.log('error message', e.responseJSON.errors);
                // Loop over the e.responseJSON.errors array and create an ul list with all the error messages
                let msg = '<ul>';
                $.each(e.responseJSON.errors, function (key, value) {
                    msg += `<li>${value}</li>`;
                });
                msg += '</ul>';
                // show the errors
                PhpProject.toast({
                    type: 'error',
                    text: msg
                });
            });
    };

    const submitFunction = function(form) {
        // Get the action property (the URL to submit)
        const action = form.attr('action');
        // Serialize the form and send it as a parameter with the post
        const pars = form.serialize();
        // Post the data to the URL
        $.post(action, pars, 'json')
            .done(function (data) {
                // show success message
                PhpProject.toast({
                    type: data.type,
                    text: data.text
                });
                // Hide the modal
                $('#modal-medication').modal('hide');
                // Rebuild the table
                loadTable();
            })
            .fail(function (e) {

                let msg = '<ul>';
                $.each(e.responseJSON.errors, function (key, value) {
                    msg += `<li>${value}</li>`;
                });
                msg += '</ul>';
                // show the errors
                PhpProject.toast({
                    type: 'error',
                    text: msg
                });
            });
    };

    // Delete a medication
    const deleteMedication = function (id) {
        // Delete the medication from the database
        let pars = {
            '_token': token,
            '_method': 'delete'
        };
        $.post(`medication/${id}`, pars, 'json')
            .done(function (data) {
                // Show toast
                PhpProject.toast({
                    type: data.type,
                    text: data.text,
                });
                // Rebuild the table
                loadTable();
            })

    }

    const loadTable = function () {

        const response = ApiService.get('qryMedication', token, '?' + filter);

        if(response.status == true) {
            $('tbody').empty();
            $.each(response.data, function (key, value) {
                let tr = `<tr>
                            <td class="text-center">${value.id}</td>
                            <td>${value.name}</td>
                            <td data-id="${value.id}" data-name="${value.name}" data-description="${value.description}" data-patients="${value.medication_patients_count}">
                                 <div class="btn-group btn-group-sm">
                                    <a href="#!" class="btn btn-outline-primary btn-prescribe" data-toggle="tooltip" title="Prescribe ${value.name}">
                                        <i class="fas fa-add"></i>
                                    </a>
                                    <a href="#!" class="btn btn-outline-success btn-edit" data-toggle="tooltip" title="Edit ${value.name}">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a href="#!" class="btn btn-outline-danger btn-delete" data-toggle="tooltip" title="Delete ${value.name}">
                                         <i class="fas fa-trash"></i>
                                    </a>
                                </div>

                            </td></tr>`;
                $('tbody').append(tr);
            });
        }
    };

    const showModal = function(method, title, action, name="", description="") {
        // Update the modal
        $('.modal-title').text(title);
        $('form').attr('action', action);
        $('#medicationName').val(name);
        $('#description').val(description);
        $('input[name="_method"]').val(method);
        // Show the modal
        $('#modal-medication').modal('show');
    }

    return {
        init: init,
        deleteFunction: deleteFunction,
        submitFunction: submitFunction,
        prescribeFunction: prescribeFunction,
        setFilter: setFilter,
        showModal: showModal,
        submitPrescription: submitPrescription
    };

}();

export default MedicationService;

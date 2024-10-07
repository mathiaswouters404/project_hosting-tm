let PrescriptionService = function () {

    let doctorStatus = false;
    let token;
    let patient = "";

    const init = function (crsf_token) {
        token = crsf_token;
        loadTable();
    };

    const setPatient = function(id) {
        doctorStatus = true;
        patient = id;
    }

    const deleteFunction = function (tag) {
        // Get data attributes from td tag
        const id = tag.closest('td').data('id');
        const medication = tag.closest('td').data('medication');
        // Set some values for Noty
        let text = `<p>Verwijder het voorschrift voor <b>${medication}</b>?</p>`;
        let type = 'warning';
        let btnText = 'Verwijder voorschrift';
        let btnClass = 'btn-success';

        // Show Confirm Dialog
        let modal = new Noty({
            type: type,
            text: text,
            buttons: [
                Noty.button(btnText, `btn ${btnClass}`, function () {
                    // Delete prescription and close modal
                    deletePrescription(id);
                    modal.close();
                }),
                Noty.button('Cancel', 'btn btn-secondary ml-2', function () {
                    modal.close();
                })
            ]
        }).show();
    };

    const submitFunction = function (form) {
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
                // Rebuild the table
                loadTable();
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

    const deletePrescription = function (id) {
        // Delete the prescription from the database
        const pars = {
            '_token': token,
            '_method': 'delete'
        };
        $.post(`prescription/${id}`, pars, 'json')
            .done(function (data) {

                // Show toast
                PhpProject.toast({
                    type: data.type,
                    text: data.text,
                });
                // Rebuild the table
                loadTable();
            })
            .fail(function (e) {
                console.log('error', e);
            });
    };

    const showModalPrescription = function (method, title, action, medication="", dosage="", reason="", startDate="", endDate="") {
        // Update the modal
        $('.modal-title').text(title);
        $('form').attr('action', action);
        // fill medication select with medications
        const response = ApiService.get('qryMedication', '{{csrf_token()}}');
        if(response.status == true) {
            $('#medication').empty();
            response.data.sort((a, b) => a.name.localeCompare(b.name))
            $.each(response.data, function(key, value) {
                let option = `<option ${value.name == medication ? "selected" : ""} value="${value.id}">${value.name}</option>`;
                $('#medication').append(option);
            });
        }
        $('#dosage').val(dosage);
        $('#reason').val(reason);
        $('#startDate').val(startDate);
        $('#endDate').val(endDate);
        $('input[name="_method"]').val(method);
        // Show the modal
        $('#modal-prescription').modal('show');
    };

    const loadTable = function() {
        const response = ApiService.get(`qryPrescription/${patient}`, token);
        if(response.status == true) {
            $('tbody').empty();
            loadPrescriptions(response.data);
        };
    };

    const loadPrescriptions = function(prescriptions){
        prescriptions.forEach(prescription => {
            // if prescription is self prescribed or user is doctor actions can be used
            const action = prescription.selfPrescribed | doctorStatus;

            const prescriptionRow =
                `<tr class="text-center">
                        <td>${prescription.medication.name}</td>
                        <td>${prescription.dosage}</td>
                        <td>${prescription.reason}</td>
                        <td>${prescription.startDate}</td>
                        <td>${prescription.endDate ? prescription.endDate : '<i class="text-danger fa fa-xmark" aria-hidden="true">'}</td>
                        <td>${prescription.selfPrescribed ? '<i class="text-danger fa fa-xmark" aria-hidden="true">' : '<i class="text-success fa fa-check" aria-hidden="true"></i>'}</td>
                        <td data-id="${prescription.id}" data-medication="${prescription.medication.name}" data-dosage="${prescription.dosage}" data-reason="${prescription.reason}" data-startDate="${prescription.startDate}" data-endDate="${prescription.endDate}" >
                             <div class="btn-group btn-group-sm ${action ? "": "disabled"}">
                                <a href="#!" class="btn btn-outline-success btn-edit ${action ? "": "disabled"}" data-toggle="tooltip" title="Edit Prescription">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="#!" class="btn btn-outline-danger btn-delete ${action ? "": "disabled"}" data-toggle="tooltip" title="Delete Prescription">
                                     <i class="fas fa-trash"></i>
                                </a>
                            </div>
                        </td></tr>`;
            $('tbody').append(prescriptionRow);
        });
    };

    return {
        init: init,
        deleteFunction: deleteFunction,
        submitFunction: submitFunction,
        showModalPrescription: showModalPrescription,
        setPatient: setPatient,
    };
}();

export default PrescriptionService;

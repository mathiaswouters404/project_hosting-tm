const Patients = (function () {
    let _token;

    let _removedId;

    /**
     * Initializes the patients overview page for the doctor and caretaker
     */
    function init(token,role) {
        _loadTable(role);
        _subscribeStaticListeners();
        _token = token;
    }

    /**
     * Loads the patients of the current user in the table
     * @private
     */
    function _loadTable(role) {
        const response = window.ApiService.get("/patients/queryPatients", _token);

        if (response.status) {
            $("#manage-patients__table").empty();

            $.each(response.data, function (key, value) {
                const tr = `
                        <tr data-id="${value.id}" data-name="${value.firstName} ${value.lastName}">
                            <td>
                                <img src="/storage/images/${value["profile_picture"]}" alt="profile-picture">
                            </td>
                            <td><span>${value.firstName} ${value.lastName}</span></td>
                            ${(role !== 2)? `
                            <td>
                                <a href="${value.id}/prescription"><i class="fa-solid fa-circle-info"></i> Dossier</a>
                            </td>
                            `:""}
                            <td>
                                <a href="/agenda/${value.id}"><i class="fa-solid fa-calendar"></i> Agenda</a>
                            </td>
                            <td>
                                <a href="/log/${value.id}?searchLogs=${value.firstName}+${value.lastName}"><i class="fa-solid fa-book"></i> Logboek</a>
                            </td>
                            <td>
                                <a href="#" onclick="Patients.questionnaire(${value.id})"><i class="fa-solid fa-circle-question"></i> Nieuwe vragenlijst</a>
                            </td>
                            <td>
                                <a href="/questionnaires/${value.id}"><i class="fa-solid fa-circle-question"></i> Antwoorden</a>
                            </td>
                            ${(role !== 3)?`
                            <td>
                                <a class="manage-patients__table__rights"><i class="fa-solid fa-shield"></i> Rechten</a>
                            </td>
                            `:""}
                            <td class="text-center">
                                <div>
                                    <button class="btn btn-outline-danger btn-delete"
                                            data-toggle="tooltip"
                                            title="Verwijderen">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    `;

                $("#manage-patients__table").append(tr);
            });

            // After reloading the table, the delete and other links in the table should be updated
            _subscribeEventListeners();
        }
    }

    /**
     * Deletes this patient from the caretaker and doctor
     * @private
     */
    function _unsubscribePatient() {
        const response = window.ApiService.del(`/patients`, _token, _removedId);

        if (response.status) {
            window.PhpProject.toast(response.data);

            // Reloads the patients in the table after unsubscribing a patient
            _loadTable();
        }
    }

    /**
     * Subscribes the event listeners after loading the user:
     * - Delete button
     * - Edit rights
     *
     * @private
     */
    function _subscribeEventListeners() {
        // Subscribes the delete user function to the delete button of each user
        $('.btn-delete').on('click', function () {
            _removedId = $(this).closest('tr').data('id');
            const name = $(this).closest('tr').data('name');

            PhpProject.popUp("warning", `<p>Unsubscribe patient?</p><p><b>${name}</b></p>`, "Continue", _unsubscribePatient);
        });

        // Subscribes the edit rights function to the edit rights link for each user
        $('.manage-patients__table__rights').on('click', function () {
            const id = $(this).closest('tr').data('id');
            const name = $(this).closest('tr').data('name');


            rights(id,name);
        });
    }

    /**
     * Makes al the static buttons on the page work:
     * - Import patient
     * - Submitting of modal forms
     *
     * @private
     */
    function _subscribeStaticListeners() {
        $('#import_patient').on('click', function () {
            $('#modal-import_patients').modal('show');
        });

        $('#modal-import_patients form').submit(function (e) {
            e.preventDefault();

            const body = $(this).serialize();

            $('#modal-import_patients').modal('hide');
            const response = window.ApiService.post("/patients", body, _token);

            if (response.status) {
                window.PhpProject.toast(response.data);

                // Reloads the patients in the table because a new patient is added
                _loadTable();
            }
        })

        $('#modal-rights form').submit(function (e) {
            e.preventDefault();

            let body = "";

            const patientId = $('#modal-rights__id').val();
            body += `_method=post&_token=${_token}&id=${patientId}`

            $("#modal-rights__rights input").each(function (key, value) {
                const name = $(value).prop('name');
                body += `&${name}=${$(value).is(":checked") ? 1 : 0}`;
            })

            $('#modal-rights').modal('hide');
            const response = window.ApiService.post("patients/editRights", body, _token, patientId);

            if (response.status) {
                window.PhpProject.toast(response.data);
            }
        })
    }

    function questionnaire(user){
        $('#modal-questionnaire').modal('show');


        QuestionnaireService.reset();
        QuestionnaireService.init(user);
        QuestionService.init();

    }

    function rights(id,name){
        const response = window.ApiService.get(`/patients/queryPatientRights/${id}`, _token);

        if (response.status) {
            const modalRights = $("#modal-rights__rights");

            modalRights.empty();

            let formContent = "";

            $.each(response.data, function (key, value) {
                let labelName = value.name.replace("_", " ");
                labelName = labelName.charAt(0).toUpperCase() + labelName.slice(1).toLowerCase();

                formContent +=
                    `<div class="form-group">
                            <input type="checkbox" name="right_id_${value.right_type_id}" id="modal-rights__${value.name}" ${value.has_right === 1 ? "checked" : ""}>
                            <label for="modal-rights__${value.name}">${labelName}</label>
                        </div>`
            });

            modalRights.append(formContent);
        }

        $('#modal-rights__id').val(id);
        $('#modal-rights .modal-title').text(`Manage rights of ${name}`);
        $('#modal-rights').modal('show');
    }

    return {
        init: init,
        questionnaire: questionnaire,
        rights: rights
    }
})();

export default Patients;

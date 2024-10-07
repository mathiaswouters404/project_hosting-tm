import EventDataService from "./EventDataService";

const EventFormService = (function () {
    let _showOnTask;
    let _showOnAppointment;
    let _showOnMedication;
    let _showOnDuration;
    let _showOnRepetition;

    let _main;

    let _editButton;
    let _submitButton;
    let _resetButton;
    let _deleteButton;
    let _excludeButton;
    let _specifyDurationSwitch;
    let _specifyRepetitionSwitch;

    function init() {
        _showOnTask = $(".show-task");
        _showOnAppointment = $(".show-appointment");
        _showOnMedication = $(".show-medication");
        _showOnDuration = $(".show-duration");
        _showOnRepetition = $(".show-repetition");

        _main = $(".modal-body .main");

        _editButton = $("#event-form__edit");
        _submitButton = $("#event-form__submit");
        _resetButton = $("#event-form__reset");
        _deleteButton = $("#event-form__delete");
        _excludeButton = $("#event-form__exclude");
        _specifyDurationSwitch = $("#duration-switch");
        _specifyRepetitionSwitch = $("#repetition-switch");
    }

    function selectType(e) {
        EventService.setSelectedEventTypeId($(e.target).val());
        showCorrectForm();
    }

    function showCorrectForm() {
        $("#event-form").removeClass('was-validated');
        _toggleMainFormField();
        _uncheckSwitches();
        _hideFormFields();

        const typeName = EventDataService.getTypeById(EventService.getSelectedEventTypeId())["name"];

        if (typeName === "task") {
            setInputField(_showOnTask, true);
            _setRequired($(".show-task input, .show-task select, .show-task textarea"), true, true);
        } else if (typeName === "appointment") {
            setInputField(_showOnAppointment, true);
            _setRequired($(".show-appointment input, .show-appointment select, .show-appointment textarea"), true, true);
        } else if (typeName === "medication") {
            EventDataService.fillMedication();
            setInputField(_showOnMedication, true);
            _setRequired($(".show-medication input, .show-medication textarea"), true, true);
        }
    }

    function _setRequired(inputField, required, resetRequiredFields) {
        if (resetRequiredFields) {
            $("input, select, textarea").prop("required", false);
        }

        inputField.prop("required", required);
    }

    function _toggleMainFormField() {
        if (EventService.getSelectedEventTypeId() !== null) {
            _main.removeClass('d-none')
        } else {
            _main.addClass('d-none')
        }
    }

    function _hideFormFields() {
        setInputField(_showOnTask, false);
        setInputField(_showOnAppointment, false);
        setInputField(_showOnMedication, false);
        setInputField(_showOnDuration, false);
        setInputField(_showOnRepetition, false);
    }

    function _uncheckSwitches() {
        setDurationSwitch(false);
        setRepetitionSwitch(false);
    }

    function setDurationSwitch(checked = true, disabled = false) {
        _specifyDurationSwitch.prop("checked", checked);
        _specifyDurationSwitch.prop("disabled", disabled);
    }

    function setRepetitionSwitch(checked = true, disabled = false) {
        _specifyRepetitionSwitch.prop("checked", checked);
        _specifyRepetitionSwitch.prop("disabled", disabled);
    }

    function resetForm() {
        const eventForm = $("#event-form");
        eventForm.removeClass('was-validated');
        $("#modal-event input, #modal-event textarea, #modal-event select").prop("disabled", false);

        const eventTypeInput = $(".event__type #event_type_id");
        eventForm[0].reset();

        eventTypeInput.val("");
        _main.addClass('d-none');

        $(".confirm-task").addClass("d-none");
    }


    function newEventButtons() {
        _editButton.addClass("d-none");
        _resetButton.removeClass("d-none");
        _submitButton.removeClass("d-none");
        _deleteButton.addClass("d-none");
        _excludeButton.addClass("d-none");
    }

    function viewEventButtons() {
        _editButton.removeClass("d-none");
        _resetButton.addClass("d-none");
        _submitButton.addClass("d-none");
        _deleteButton.addClass("d-none");
        _excludeButton.addClass("d-none");
    }

    function editEventButtons() {
        _editButton.addClass("d-none");
        _resetButton.addClass("d-none");
        _submitButton.removeClass("d-none");
        _deleteButton.removeClass("d-none");

        const event = EventDataService.getEventById(EventService.getSelectedEventId());
        if (event["time_unit_id"] !== null && event["event_type_name"] !== "questionnaire") {
            _excludeButton.removeClass("d-none");
        }
    }

    function showEventModal() {
        $('#modal-event').modal("show");
    }

    function hideEventModal() {
        $('#modal-event').modal("hide");
    }

    function disableFormInputs(value = true) {
        $("#modal-event input, #modal-event textarea, #modal-event select").prop("disabled", value);
    }

    function setInputField(inputField, visible = true, disabled = false) {
        if (visible) {
            inputField.removeClass("d-none");
        } else {
            inputField.addClass("d-none");
        }

        inputField.prop("disabled", disabled);
    }

    function saveStartDate() {
        EventDataService.setStartDate($(".event__startDate #start_date").val());
    }

    function toggleDurationInputs(e) {
        if ($(e.target).is(":checked")) {
            _showOnDuration.removeClass("d-none");
            _setRequired($(".show-duration input"), true, false);
        } else {
            _showOnDuration.addClass("d-none");
            _setRequired($("#duration_date"), false, false);
        }
    }

    function toggleRepetitionInputs(e) {
        if ($(e.target).is(":checked")) {
            EventDataService.fillTimeUnits();
            _showOnRepetition.removeClass("d-none");
            _setRequired($(".show-repetition input, .show-repetition select"), true, false);
        } else {
            _showOnRepetition.addClass("d-none");
            _setRequired($(".show-repetition input, .show-repetition select"), false, false);
        }
    }

    return {
        newEventButtons: newEventButtons,
        viewEventButtons: viewEventButtons,
        editEventButtons: editEventButtons,
        showCorrectForm: showCorrectForm,
        selectType: selectType,
        resetForm: resetForm,
        showEventModal: showEventModal,
        hideEventModal: hideEventModal,
        disableFormInputs: disableFormInputs,
        setInputField: setInputField,
        setDurationSwitch: setDurationSwitch,
        setRepetitionSwitch: setRepetitionSwitch,
        saveStartDate: saveStartDate,
        toggleDurationInputs: toggleDurationInputs,
        toggleRepetitionInputs: toggleRepetitionInputs,
        init: init,
    }
})();

export default EventFormService;

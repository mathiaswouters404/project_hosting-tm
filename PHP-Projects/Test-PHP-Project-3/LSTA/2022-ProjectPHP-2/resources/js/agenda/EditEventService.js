const EditEventService = (function () {
    function editEvent() {
        SubmitEventService.setSubmitMode("edit");
        EventFormService.editEventButtons();

        EventFormService.disableFormInputs(false);
        $(".event__type #event_type_id").prop("disabled", true);
    }

    return {
        editEvent: editEvent,
    }
})();

export default EditEventService;

const NewEventService = (function () {
    function newEvent() {
        EventFormService.resetForm();
        EventFormService.newEventButtons();
        SubmitEventService.setSubmitMode("new");
        EventFormService.showEventModal();
    }

    return {
        newEvent: newEvent,
    }
})();

export default NewEventService;

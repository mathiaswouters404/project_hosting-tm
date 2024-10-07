const DeleteEventService = (function () {
    function deleteEvent() {
        const id = EventService.getSelectedEventId();
        const event = EventDataService.getEventById(id);

        PhpProject.popUp("success",
            `Are you sure you want to delete the event "<b>${event["name"]}</b>"?`,
            "Delete",
            _submitDeleteEvent,
            $('#modal-event')
        );
    }

    function excludeEventDate() {
        const date = EventDataService.getClickedEventDate();

        PhpProject.popUp("success",
            `Are you sure you want to delete the event for the date "<b>${date}</b>"?`,
            "Delete",
            _submitExcludeEvent,
            $('#modal-event')
        );
    }

    function _submitExcludeEvent() {
        const eventId = EventService.getSelectedEventId();
        const date = EventDataService.getClickedEventDate();
        const body = `_token=${AgendaService.getToken()}&_method=post&date=${date}&event_id=${eventId}`;

        const response = ApiService.post("/events/excludeEvent", body, AgendaService.getToken());
        
        window.Validator.validate(response);

        EventFormService.resetForm();

        window.AgendaService.refreshDates();
    }

    function _submitDeleteEvent() {
        const response = ApiService.del("/events", AgendaService.getToken(), EventService.getSelectedEventId());

        window.Validator.validate(response);

        EventFormService.resetForm();

        window.AgendaService.refreshDates();
    }

    return {
        deleteEvent: deleteEvent,
        excludeEventDate: excludeEventDate
    }
})();

export default DeleteEventService;

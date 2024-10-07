const EventService = (function () {
    let _selectedEventId = null;
    let _selectedEventTypeId = null;

    function init() {
        EventFormService.init();

        EventDataService.fillEventTypes();
        ViewEventService.loadElements();

        $('#modal-event').on("hidden.bs.modal", EventFormService.resetForm);
    }

    function getSelectedEventId() {
        return _selectedEventId;
    }

    function setSelectedEventId(eventId) {
        _selectedEventId = eventId;
        setSelectedEventTypeId(EventDataService.getEventById(eventId)["event_type_id"]);
    }

    function getSelectedEventTypeId() {
        return _selectedEventTypeId;
    }

    function setSelectedEventTypeId(eventTypeId) {
        _selectedEventTypeId = eventTypeId;
    }

    return {
        init: init,
        getSelectedEventId: getSelectedEventId,
        setSelectedEventId: setSelectedEventId,
        getSelectedEventTypeId: getSelectedEventTypeId,
        setSelectedEventTypeId: setSelectedEventTypeId,
    };
})();

export default EventService;

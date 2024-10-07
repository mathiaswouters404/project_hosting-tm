const ConfirmEventService = (function () {
    function confirmEvent(e) {
        if (!$(e.target).closest("div").find("i").hasClass("fa-square-check")) {
            const selectedEventId = EventService.getSelectedEventId();

            const response = ApiService.get("/events/confirmEvent", AgendaService.getToken(), selectedEventId);
            Validator.validate(response, () => {
                const confirmIcon = $(".confirm-task").find("i");
                confirmIcon.addClass("fa-solid");
                confirmIcon.addClass("fa-square-check");
                window.AgendaService.refreshDates();
            });
        }
    }

    return {
        confirmEvent: confirmEvent,
    }
})();

export default ConfirmEventService;

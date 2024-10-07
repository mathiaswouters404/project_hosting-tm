const LogService = (function () {
    function newLog() {
        $("#modal-log").modal("show");
        _fillEventOptions();
    }

    function submitLog(e) {
        e.preventDefault();

        const body = $("#modal-form").serialize() + `&patient_id=${AgendaService.getAgendaUserId()}`;
        const response = ApiService.post("/log", body, AgendaService.getToken())

        Validator.validate(response, () => {
            $("#modal-form select, #modal-form input, #modal-form textarea").val("");
            $("#modal-log").modal("hide");
        });
    }

    function _fillEventOptions() {
        const eventSelect = $("#log-event-id");

        if (eventSelect.children().length === 1) {
            $.each(ShowEventService.getWeeklyEvents(), (key, value) => {
                eventSelect.append(`
                    <option value="${value["id"]}">${value["name"]}</option>
                `);
            })
        }
    }

    return {
        newLog: newLog,
        submitLog: submitLog,
    }
})();

export default LogService;

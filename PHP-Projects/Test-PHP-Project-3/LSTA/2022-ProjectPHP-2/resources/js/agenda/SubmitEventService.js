const SubmitEventService = (function () {
    let _submitMode;

    function submitForm(e) {
        e.preventDefault();

        ClientSideValidation.validate("event-form", () => {
            const eventTypeName = EventDataService.getTypeById(EventService.getSelectedEventTypeId())["name"];
            let body;
            let response;
            let duration;

            const startDate = $("#start_date").val();
            const durationDate = $("#duration_date").val();

            if (durationDate !== "") {
                duration = _calculateDuration(startDate, durationDate);
            } else {
                duration = "5";
            }

            if (eventTypeName === "medication") {
                $(".event__name #name").val("Medication");

                let description = "";
                $.each($(".medication-list__name"), function (key, value) {
                    description += "- " + $(value).text() + "\n";
                });

                $(".event__description #description").val(description);
            }

            const repetitionSwitch = $("repetition-switch");
            if (repetitionSwitch.val() === 0) {
                repetitionSwitch.val(null);
            }

            if (_submitMode === "new") {
                body = $("#event-form").serialize() + "&patient_id=" + AgendaService.getAgendaUserId() + "&duration=" + duration;
                response = ApiService.post("/events", body, AgendaService.getToken());
            } else {
                body = $('input[name!=event_type_id], select, textarea', $("#event-form")).serialize() + "&patient_id=" + AgendaService.getAgendaUserId() + "&duration=" + duration;
                response = ApiService.put("/events", body, EventService.getSelectedEventId());
            }

            window.Validator.validate(response, () => {
                EventFormService.hideEventModal();
                EventFormService.resetForm();
                window.AgendaService.refreshDates();
            });
        });
    }

    function _calculateDuration(startDateTime, endDateTime) {
        const difference = new Date(endDateTime) - new Date(startDateTime);
        return Math.floor((difference / 1000) / 60);
    }

    function getSubmitMode() {
        return _submitMode;
    }

    function setSubmitMode(submitMode) {
        _submitMode = submitMode;
    }

    return {
        submitForm: submitForm,
        getSubmitMode: getSubmitMode,
        setSubmitMode: setSubmitMode,
    }
})();

export default SubmitEventService;

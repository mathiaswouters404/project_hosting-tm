import EventMedicationService from "./EventMedicationService";

const ViewEventService = (function () {
    let _event;

    let _eventTypeInput;
    let _nameInput;
    let _descriptionInput;
    let _locationInput;
    let _contactPersonInput;
    let _startDateInput;
    let _durationDateInput;
    let _intervalInput;
    let _timeUnitInput;
    let _endDateInput;

    function showEvent(e) {
        EventDataService.setClickedEventDate($(e.target).closest(".data-event-date").data("event_date"));
        EventService.setSelectedEventId($(e.target).closest("div.event").data("event-id"));

        fillFormWithEventData();
        EventFormService.disableFormInputs();
        EventFormService.viewEventButtons();
    }

    function fillFormWithEventData() {
        const id = EventService.getSelectedEventId();

        _event = EventDataService.getEventById(id);

        EventService.setSelectedEventTypeId(_event["event_type_id"].toString());
        EventFormService.showCorrectForm();

        // Event type
        _eventTypeInput.val(_event["event_type_id"]);
        EventFormService.setInputField(_eventTypeInput, true, false);

        EventFormService.setDurationSwitch();

        const eventDurationInput = $(".event__duration");
        EventFormService.setInputField(eventDurationInput);

        _fillMedicationData();
        _fillRepetitionData();
        _fillTaskData();

        // Event name
        _nameInput.val(_event["name"]);

        // Event location
        _locationInput.val(_event["location"]);

        // Contact person
        _contactPersonInput.val(_event["contact_person"]);

        const startDate = new Date(_event["start_date"] + " " + _event["start_hour"]);

        // Start date
        _startDateInput.val(window.moment(startDate).format().substring(0, 16));

        // Duration
        _durationDateInput.val(window.moment(new Date(startDate.getTime() + _event["duration"] * 60000)).format().substring(0, 16));

        if (_event["duration"] >= 5) {
            EventFormService.setDurationSwitch(true);
            EventFormService.setInputField(eventDurationInput, true, false);
        }

        if (_event["time_unit_id"] === null) {
            EventFormService.setRepetitionSwitch(false, true);
            EventFormService.setInputField($(".show-repetition"), false);
            $("#end_date").val(null);
            $("#interval").val(null);
        }

        EventFormService.showEventModal();
    }

    function _fillMedicationData() {
        EventMedicationService.clearMedications();

        if (_event["event_type_name"] === "medication" && _event["description"] !== null) {

            const medications = _event["description"].replaceAll("- ", "").replaceAll("\r", "").split("\n");
            $.each(medications, (key, value) => {
                EventMedicationService.pushMedication(EventDataService.getMedicationByName(value));
            });
        } else {
            _descriptionInput.val(_event["description"]);
        }
    }

    function _fillRepetitionData() {
        if (_event["interval"] !== null) {
            EventDataService.fillTimeUnits();

            // Interval
            _intervalInput.val(_event["interval"]);

            // Time unit
            _timeUnitInput.val(_event["time_unit_id"]);

            // End date
            _endDateInput.val(window.moment(new Date(_event["end_date"])).format().substring(0, 10));

            EventFormService.setRepetitionSwitch();
            EventFormService.setInputField($(".show-repetition"));
        }
    }

    function _fillTaskData() {
        const confirmTask = $(".confirm-task");

        if (_event["event_type_name"] === "task" && EventDataService.userCanConfirmEvents()) {
            confirmTask.removeClass("d-none");
            const confirmIcon = confirmTask.find("i");

            if (_event["confirmed"] === 1) {
                confirmIcon.removeClass("fa-regular");
                confirmIcon.removeClass("fa-square");
                confirmIcon.addClass("fa-solid");
                confirmIcon.addClass("fa-square-check");
            } else {
                confirmIcon.removeClass("fa-solid");
                confirmIcon.removeClass("fa-square-check");
                confirmIcon.addClass("fa-regular");
                confirmIcon.addClass("fa-square");
            }
        }
    }

    function loadElements() {
        _eventTypeInput = $(".event__type #event_type_id");
        _nameInput = $(".event__name #name");
        _descriptionInput = $(".event__description #description");
        _locationInput = $(".event__location #location");
        _contactPersonInput = $(".event__contactPerson #contact_person");
        _startDateInput = $(".event__startDate #start_date");
        _durationDateInput = $(".event__duration #duration_date");
        _intervalInput = $(".event__interval #interval");
        _timeUnitInput = $(".event__timeUnit #time-unit");
        _endDateInput = $(".event__endDate #end_date");

    }

    return {
        showEvent: showEvent,
        fillFormWithEventData: fillFormWithEventData,
        loadElements: loadElements,
    }
})();

export default ViewEventService;

const EventDataService = (function () {
    let _eventTypes = null;
    let _timeUnits = null;
    let _medication = null;

    let _userCanConfirmEvents = null;

    let _previousStartDate = null;
    let _clickedEventDate;

    /**
     * Queries the event types from the database
     * Adds them to the _eventTypes variable
     * Adds them to the eventTypes dropdown
     */
    function fillEventTypes() {
        const response = window.ApiService.get("/events/queryEventTypes", AgendaService.getToken());

        if (response.status) {
            _eventTypes = response.data;

            $.each(_eventTypes, (key, value) => {
                let name = value.name;
                name = name.substring(0, 1).toUpperCase() + name.substring(1);

                if (name !== "Questionnaire") {
                    $(".event__type #event_type_id").append(`<option value="${value.id}">${name}</option>`);
                }
            })
        }
    }

    /**
     * Queries the time units from the database
     * Adds them to the _timeUnits variable
     * Adds them to the timeUnits dropdown
     */
    function fillTimeUnits() {
        if (_timeUnits === null) {
            initTimeUnits();
        }
    }


    function initTimeUnits(){
        const response = ApiService.get("/events/queryTimeUnits", AgendaService.getToken());
        if (response.status) {
            _timeUnits = response.data;
            $.each(_timeUnits, (key, value) => {
                let name = value.name;
                name = name.substring(0, 1).toUpperCase() + name.substring(1);

                $(".event__timeUnit #time-unit").append(`<option value="${value.id}">${name}</option>`);
            })
        }
    }

    /**
     * Queries the medications form the patient from the database
     * Adds them to the _medications variable
     * Adds them to the medications dropdown
     */
    function fillMedication() {
        if (_medication === null) {
            const response = window.ApiService.get("/events/queryMedications", AgendaService.getToken(), AgendaService.getAgendaUserId());

            if (response.status) {
                _medication = [];

                $.each(response.data, (key, value) => {
                    const medication = value["medication"];
                    _medication.push(medication);

                    let name = medication["name"];
                    name = name.substring(0, 1).toUpperCase() + name.substring(1);

                    $(".event__name #medication").append(`<option value="${medication["id"]}">${name}</option>`);
                })
            }
        }
    }

    /**
     * Returns the EventType for the given id
     *
     * @param typeId
     * @returns {id, name}
     */
    function getTypeById(typeId) {
        const id = parseInt(typeId, 10);
        let type;

        $.each(_eventTypes, (key, value) => {
            if (value["id"] === id) {
                type = value;
            }
        });

        return type;
    }

    /**
     * Returns the Medication for the given id
     *
     * @param medicationId
     * @returns {id, name, description}
     */
    function getMedicationById(medicationId) {
        const id = parseInt(medicationId, 10);
        let medication;

        $.each(_medication, (key, value) => {
            if (value.id === id) {
                medication = {
                    id: medicationId,
                    name: value["name"],
                    description: value["description"]
                };
            }
        });

        return medication;
    }

    /**
     * Returns the Medication for the given name
     *
     * @param medicationName
     * @returns {id, name, description}
     */
    function getMedicationByName(medicationName) {
        let medication;

        $.each(_medication, (key, value) => {
            if (value["name"] === medicationName) {
                medication = {
                    id: value["id"],
                    name: value["name"],
                    description: value["description"]
                };
            }
        });
        return medication;
    }

    /**
     * Returns the Event for the given id
     *
     * @param eventId
     * @returns {*}
     */
    function getEventById(eventId) {
        let event;

        $.each(ShowEventService.getWeeklyEvents(), (key, value) => {
            if (value["id"] === eventId) {
                event = value;
            }
        });

        return event;
    }

    function getStartDate() {
        return  _previousStartDate;
    }

    function setStartDate(date) {
        _previousStartDate = date;
    }

    function getClickedEventDate() {
        return _clickedEventDate;
    }

    function setClickedEventDate(date) {
        _clickedEventDate = date;
    }

    function userCanConfirmEvents() {
        if (_userCanConfirmEvents === null) {
            const response = ApiService.get("/user/queryCanConfirmEvents", AgendaService.getToken());

            if (response["status"]) {
                _userCanConfirmEvents = response["data"];
            } else {
                _userCanConfirmEvents = false;
            }
        }

        return _userCanConfirmEvents;
    }

    return {
        fillEventTypes: fillEventTypes,
        fillTimeUnits: fillTimeUnits,
        fillMedication: fillMedication,
        getTypeById: getTypeById,
        getMedicationById: getMedicationById,
        getMedicationByName: getMedicationByName,
        getEventById: getEventById,
        getStartDate: getStartDate,
        setStartDate: setStartDate,
        initTimeUnits: initTimeUnits,
        getClickedEventDate: getClickedEventDate,
        setClickedEventDate: setClickedEventDate,
        userCanConfirmEvents: userCanConfirmEvents,
    };
})();

export default EventDataService;

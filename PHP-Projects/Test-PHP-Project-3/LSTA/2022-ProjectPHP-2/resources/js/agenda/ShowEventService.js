const ShowEventService = (function () {

    let _weeklyEvents;
    let _eventMapping = {};
    let _overlapGraph = {};
    let _agendaEventsCounter = 0;

    let _maxDepth = 0;
    let _notMaxStackMargin = {
        baseMargin: 0,
        currentIteration: 0,
        maxIterations: 0
    };
    let _maxStackMargin = {
        previousWidth: 0,
        previousIteration: 0,
        globalIterations: 0,
    };

    /**
     * Loads the events for the current week for the current user in the agenda
     * @private
     */
    function loadEventData(monday) {
        _agendaEventsCounter = 0;

        // Create a date string for the date of monday
        const mondayString = monday.getFullYear() + "-" + (monday.getMonth() + 1) + "-" + monday.getDate();

        // Create a body for the post request with the date in it
        const body = `_method=post&_token=${AgendaService.getToken()}&monday=${mondayString}`;

        let response;
        if (AgendaService.getRole() === "Patient" || AgendaService.getRole() === "Mantelzorger") {
            // Send the post request to the server to get the weekly events
            // This data will include extra info about the event
            response = window.ApiService.post("/agenda/weeklyEvents", body, AgendaService.getToken(), AgendaService.getAgendaUserId());
        } else {
            // Send the post request to the server to get the weekly events
            // This data won't include any info about the event
            response = window.ApiService.post("/agenda/weeklyEventsWithoutInfo", body, AgendaService.getToken(), AgendaService.getAgendaUserId());
        }

        if (response.status) {
            const data = response.data;

            console.log(data)
            _weeklyEvents = data;
            _eventMapping = {};

            // For each event we get, we do the same iteration
            $.each(data, (key, value) => {
                // If there is no array of dates, we only place this event once in the agenda
                if (value["date_list"].length === 0) {
                    _addToEventMapping(value, value["start_date"]);
                }

                // If there is an array of dates, we place an event in the agenda for each one
                else {
                    $.each(value["date_list"], (key, date) => {
                        _addToEventMapping(value, date);
                    });
                }
            });

            _sortEventMappings();
            _placeEventsInAgenda();
        }
    }

    function _addToEventMapping(event, date) {
        const startTime = new Date(date + " " + event["start_hour"]).getTime();
        const endTime = startTime + event["duration"] * 60000;
        const eventMap = {
            eventId: event["id"],
            startTime: startTime,
            endTime: endTime,
            maxOverlapping: 0,
            amountOfOverlaps: 0,
            overlappingMaxOverlapping: [],
            maxOverlapInStack: 0,
            eventData: event
        }

        const eventId = event["id"];

        if (_eventMapping[date] === undefined) {
            _eventMapping[date] = [eventMap];
            _overlapGraph[date] = {}
        } else {
            _eventMapping[date].push(eventMap);
        }

        _overlapGraph[date][eventId] = [];
    }

    function _sortEventMappings() {
        Object.keys(_eventMapping).forEach((key) => {
            const eventList = _eventMapping[key];
            eventList.sort((a, b) => {
                if (a["startTime"] !== b["startTime"]){
                    return a["startTime"] - b["startTime"];
                } else {
                    return b["eventData"]["duration"] - a["eventData"]["duration"];
                }
            });
        });
    }

    function _placeEventsInAgenda() {
        const keys = Object.keys(_eventMapping);

        keys.forEach((key) => {
            const eventList = _eventMapping[key];

            eventList.forEach((selectedEventMapping) => {
                eventList.forEach((checkForOverlap) => {
                    if (_eventsOverlap(selectedEventMapping, checkForOverlap) || _eventsOverlap(checkForOverlap, selectedEventMapping)) {
                        _overlapGraph[key][selectedEventMapping["eventId"]].push(checkForOverlap["eventId"]);
                        selectedEventMapping["amountOfOverlaps"]++;
                    }
                });
            });

            eventList.forEach((selectedEventMapping) => {
                const graph = _overlapGraph[key];
                const eventId = selectedEventMapping["eventId"];
                _setMaxOverlappingDepth([], graph, eventId, graph[eventId]);
                selectedEventMapping["maxOverlapping"] = _maxDepth;

                _maxDepth = 0;
            });

            eventList.forEach((selectedEventMapping) => {
                const graph = _overlapGraph[key];
                const eventId = selectedEventMapping["eventId"];

                graph[eventId].forEach(overlappingEventId => {
                    selectedEventMapping["overlappingMaxOverlapping"].push(_getEventMappingById(overlappingEventId)["maxOverlapping"]);
                });

                selectedEventMapping["maxOverlapInStack"] = Math.max(...selectedEventMapping["overlappingMaxOverlapping"]);
            });

            eventList.forEach((eventMapping) => {
                _placeEventInAgenda(eventMapping, key);
            });
        });
    }

    function _getEventMappingById(eventId) {
        let result;

        Object.keys(_eventMapping).forEach((key) => {
            $.each(_eventMapping[key], (k, v) => {
                if (v["eventId"] === eventId) {
                    result = v;
                }
            });
        });

        return result;
    }

    function _eventsOverlap(event1, event2) {
        return event1["startTime"] <= event2["endTime"] && event1["endTime"] >= event2["endTime"] ||
            event1["startTime"] <= event2["startTime"] && event1["endTime"] >= event2["startTime"];
    }

    function _setMaxOverlappingDepth(visited, graph, root, intersectWith, depth = 0) {
        _maxDepth = Math.max(depth, _maxDepth);

        if (!visited.includes(root)) {
            visited.push(root);

            const intersection = _intersectArrays(graph[root], intersectWith);

            intersection.forEach(child => {
                _setMaxOverlappingDepth(visited, graph, child, intersectWith, depth + 1);
            });
        }
    }

    function _intersectArrays(array1, array2) {
        return array1.filter(value => array2.includes(value));
    }

    /**
     * Places an event in the agenda on the right date and right hour
     * Sets the height of the event to the duration of it
     * @param eventMapping
     * @param date
     * @private
     */
    function _placeEventInAgenda(eventMapping, date) {
        const event = eventMapping["eventData"];

        if (AgendaService.getRole() === "Patient" || AgendaService.getRole() === "Mantelzorger") {
            if (event["event_type_name"] === "questionnaire") {
                $(`.agenda__events--events *[data-event_date="${date}"]`).append(`
                    <div data-questionnaire-id="${event["questionnaire_id"]}" data-event-id="${event["id"]}" onclick="AgendaQuestionnaireService.showAnswerForm(event)" class="event event_${event["id"]} agenda_events_count_${_agendaEventsCounter}">
                        <div class="event_${event["event_type_name"]} agenda-event">
                            <div>${event["name"]}</div>
                        </div>
                    </div>
                `);
            } else {
                $(`.agenda__events--events *[data-event_date="${date}"]`).append(`
                    <div data-event-id="${event["id"]}" onclick="ViewEventService.showEvent(event)" class="event event_${event["id"]} agenda_events_count_${_agendaEventsCounter}">
                        <div class="event_${event["event_type_name"]} agenda-event">
                            <div>${event["name"]}</div>
                        </div>
                    </div>
                `);
            }
        } else {
            $(`.agenda__events--events *[data-event_date="${date}"]`).append(`
                    <div class="event agenda_events_count_${_agendaEventsCounter}">
                        <div style="background-color: #aaa;">
                            <div>Bezet</div>
                        </div>
                    </div>
            `)
        }

        // Takes the start time and parses it to integers
        const timeArray = event["start_hour"].split(':');
        const hours = parseInt(timeArray[0], 10);
        const minutes = parseInt(timeArray[1], 10);
        const seconds = parseInt(timeArray[2], 10);

        // We turn the time to hours bv. 14:30 becomes 14.5
        const startHour = hours + minutes / 60 + seconds / 360;

        // The duration of an event is given in minutes, so it is turned to hours
        const duration = event.duration / 60;

        // Create percentages of the startHour and duration
        // These percentages will be the exact values used in the styling
        const top = startHour / 24 * 100;
        const height = duration / 24 * 100;
        let width;
        let margin = 0;
        if (eventMapping["maxOverlapping"] === eventMapping["maxOverlapInStack"]) {
            width = 100 / (eventMapping["maxOverlapping"]);

            if (eventMapping["amountOfOverlaps"] > 1 ) {
                if (width !== _maxStackMargin["previousWidth"]) {
                    margin = 0;
                    _maxStackMargin["previousWidth"] = width;
                    _maxStackMargin["previousIteration"] = 1;
                } else {
                    margin = _maxStackMargin["previousWidth"] * _maxStackMargin["previousIteration"];
                    _maxStackMargin["previousIteration"] += 1;
                    _maxStackMargin["globalIterations"] += 1;
                }
            }

            if (margin >= 100) {
                const eventsInRow = _overlapGraph[date][eventMapping["eventId"]];
                const eventsCountBefore = eventsInRow.lastIndexOf(eventMapping["eventId"]);
                margin = eventsCountBefore * width;
                _maxStackMargin["previousWidth"] = width;
                _maxStackMargin["previousIteration"] = eventsCountBefore + 1;
            }
        } else {

            const slimElements = eventMapping["overlappingMaxOverlapping"].filter(e => e === eventMapping["maxOverlapInStack"]).length
            const wideElements = eventMapping["amountOfOverlaps"] - slimElements

            const smallWidth = 100 / eventMapping["maxOverlapInStack"];
            const allSmallElementsWidth = smallWidth * slimElements;
            const restWidth = 100 - allSmallElementsWidth;

            width = restWidth  / wideElements;

            if (_notMaxStackMargin["currentIteration"] >= _notMaxStackMargin["maxIterations"]) {
                margin = (100 / eventMapping["maxOverlapInStack"]) * slimElements;
                _notMaxStackMargin["maxIterations"] = eventMapping["overlappingMaxOverlapping"].length - slimElements - 1;
                _notMaxStackMargin["currentIteration"] = 0;
                _notMaxStackMargin["baseMargin"] = margin;
            } else {
                margin = _notMaxStackMargin["baseMargin"] + width * (_notMaxStackMargin["currentIteration"] + 1);
                _notMaxStackMargin["currentIteration"]++;
            }
        }



        const eventDiv = $(`.agenda_events_count_${_agendaEventsCounter}`);

        // We set the top and height to the calculated percentages and the events will have the correct height and location in the agenda
        eventDiv.css('top', `${top}%`);
        eventDiv.css('height', `${height}%`);
        eventDiv.css('width', `${width}%`);
        eventDiv.css('margin-left', `${margin}%`);

        _agendaEventsCounter++;
    }

    function getWeeklyEvents() {
        return _weeklyEvents;
    }

    return {
        loadEventData: loadEventData,
        getWeeklyEvents: getWeeklyEvents,
    }
})();

export default ShowEventService;

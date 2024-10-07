import ShowEventService from "./ShowEventService";

const AgendaService = (function () {
    let _monday;

    let _role;
    let _agendaUserId;
    let _token;

    /**
     * Initializes the agenda by filling all dates and scrolling to 6h
     * Makes the buttons for navigation trough weeks working
     */
    function init(token, agendaUserId, role) {
        _token = token;
        _agendaUserId = agendaUserId;
        _role = role;
        _setMonday();
        refreshDates();
        _subscribeEventListeners();
        _createPartitions();
    }

    function _createPartitions() {
        const separators = $(".agenda__events--separators");

        for (let i = 0; i < 24; i++) {
            const element = $(`<div class="agenda__events--separator"></div>`);
            separators.append(element);

            const top = i / 24 * 150;

            element.css("top", `${top}%`);
        }
    }

    /**
     * Date of monday for the current week
     * @returns {date} The date for the monday of the selected week
     */
    function getMonday() {
        return _monday;
    }

    /**
     * Sets monday to the monday of the next week
     * Loads the dates of the new week
     * @private
     */
    function _next() {
        _monday.setDate(_monday.getDate() + 7);
        refreshDates();
    }

    /**
     * Sets monday to the monday of the previous week
     * Loads the dates of the previous week
     * @private
     */
    function _previous() {
        _monday.setDate(_monday.getDate() - 7);
        refreshDates();
    }

    /**
     * Loads the agenda with the dates that are in the week of the global variable 'monday'
     * - Loads the week title
     * - Loads the dates for each day
     * @private
     */
    function refreshDates() {
        _loadWeek();
        _loadWeekDays();
        _resetViewBox();

        ShowEventService.loadEventData(_monday);
    }

    /**
     * Sets the global variable 'monday' to the date of monday in the current week
     * @private
     */
    function _setMonday() {
        // Gets the current date
        const date = new Date();

        // Gets the index of the current day
        const day = date.getDay();

        // Day === 0: The current day is a sunday so subtract 6 days from the date
        // Day != 0: Add 1 to the date since the JS weekday systems starts with a sunday at index 0
        const diff = date.getDate() - day + (day === 0 ? -6 : 1);

        _monday = new Date(date.setDate(diff));
    }

    /**
     * Fills in the week title with date of the first and last day of the week
     * Format: d Month yyyy - d Month yyyy
     * @private
     */
    function _loadWeek() {
        // Copy the value of monday into a new date object
        const sunday = new Date(_monday);

        // Sunday is 6 days after monday
        sunday.setDate(sunday.getDate() + 6);

        // Create formatting options for the date
        const weekOptions = { day: "numeric", month: "long", year: "numeric" };

        // Format the date of monday and sunday and convert it to a string
        const mondayString = new Intl.DateTimeFormat(
            "nl-BE",
            weekOptions
        ).format(_monday);
        const sundayString = new Intl.DateTimeFormat(
            "nl-BE",
            weekOptions
        ).format(sunday);

        // Fill the date field with the newly created string
        $(".agenda__date--date").text(`${mondayString} - ${sundayString}`);
    }

    /**
     * Fills in the column names for the days of the week
     * Format: day, d month
     * @private
     */
    function _loadWeekDays() {
        const currentDay = new Date(_monday);
        const agendaDays = $(".agenda__days--content > ul");
        const agendaEvents = $(".agenda__events--events");

        // Create formatting options for the dates
        const weekDayOptions = {
            weekday: "short",
            day: "numeric",
            month: "short",
        };
        const eventDateOptions = {
            year: "numeric",
            month: "2-digit",
            day: "2-digit"
        }


        // Empty the column names and the events
        agendaDays.empty();
        agendaEvents.empty();

        // Fill in the column names with newly formatted dates
        for (let i = 0; i < 7; i++) {
            const weekDay = new Intl.DateTimeFormat(
                "nl-BE",
                weekDayOptions
            ).format(currentDay);

            const eventDate = new Intl.DateTimeFormat(
                "fr-CA",
                eventDateOptions
            ).format(currentDay);

            agendaDays.append(`<li>${weekDay}</li>`);
            agendaEvents.append(`<div class="data-event-date" data-event_date="${eventDate}"></div>`)

            // Each iteration the date is incremented by one day
            currentDay.setDate(currentDay.getDate() + 1);
        }
    }

    /**
     * Subscribes listener callbacks to click events on the arrow buttons
     * @private
     */
    function _subscribeEventListeners() {
        $(".agenda__date--previous").on("click", () => _previous());
        $(".agenda__date--next").on("click", () => _next());
        $(".legend--button").on("click", _selectEvents);
    }

    /**
     * Makes the user able to hide and show certain types of events
     * Shows or hides events of this type by clicking on the corresponding event type in the legend
     * @private
     */
    function _selectEvents() {
        const eventType = $(this).data("event-type-name");
        const i = $(this).find("i")

        if (i.hasClass("fa-solid")) {
            i.removeClass("fa-solid");
            i.removeClass("fa-square-check");
            i.addClass("fa-regular");
            i.addClass("fa-square");
            $(`.agenda-event.event_${eventType}`).parent().hide();
        } else {
            i.removeClass("fa-regular");
            i.removeClass("fa-square");
            i.addClass("fa-solid");
            i.addClass("fa-square-check");
            $(`.agenda-event.event_${eventType}`).parent().show();
        }
    }

    /**
     * Scrolls to 6h in the agenda since the hours before aren't that commonly used
     * @private
     */
    function _resetViewBox() {
        document.querySelector("#agenda__events--hours--6").scrollIntoView();
    }

    function getToken() {
        return _token;
    }

    function getAgendaUserId() {
        return _agendaUserId;
    }

    function getRole() {
        return _role;
    }

    return {
        init: init,
        getMonday: getMonday,
        refreshDates: refreshDates,
        getToken: getToken,
        getAgendaUserId: getAgendaUserId,
        getRole: getRole
    };
})();

export default AgendaService;

// TODO - Hide repetition if non repetition event

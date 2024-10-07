<div class="modal" id="modal-event">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header d-flex align-items-center">
                <h5 class="modal-title">
                    <div class="confirm-task" onclick="ConfirmEventService.confirmEvent(event)">
                        <i class="fa-regular fa-square"></i>
                        <span>Confirmed</span>
                    </div>
                </h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <form action="" method="post" id="event-form" novalidate class="needs-validation" onsubmit="SubmitEventService.submitForm(event)">
                    @method('')
                    @csrf

                    {{-- Mode of the form: new / edit --}}
                    <input id="form-mode" type="hidden" value="new">

                    {{-- Event Type --}}
                    <div class="event__type">
                        {{-- Create new event --}}
                        <div>
                            <label for="event_type_id">Event Type<span class="event__type--edit"></span></label>
                            <select class="form-control" name="event_type_id" onchange="EventFormService.selectType(event)" id="event_type_id">
                                <option value="">Choose an event type</option>
                            </select>
                        </div>
                    </div>

                    {{-- Main --}}
                    <div class="main">
                        {{-- Event name --}}
                        <div class="event__name mt-4">
                            {{-- Not a medication event: Show name input field --}}
                            <div class="form-group show-task show-appointment">
                                <label for="name">Title</label>
                                <input type="text" name="name" id="name" minlength="0" class="form-control" value="">
                                <div class="invalid-feedback">Please choose a valid title</div>
                            </div>

                            {{-- Medication event: Show dropdown with medications --}}
                            <div class="from-group show-medication">
                                <label for="medication">Medication</label>
                                <select class="form-control" onchange="EventMedicationService.addMedication()" name="medication" id="medication">
                                    <option value="">Choose your medication</option>
                                </select>

                                {{-- Auto generated list of selected medications --}}
                                <p class="mt-3">Selected medications:</p>
                                <ul id="medication-list">
                                </ul>
                            </div>
                        </div>

                        {{-- Event description --}}
                        <div class="event__description show-task show-appointment mt-3">
                            {{-- Input for event description --}}
                            <div>
                                <label for="description">Event description</label>
                                <textarea id="description" name="description" minlength="3" class="h-100 col-12 form-control"></textarea>
                                <div class="invalid-feedback">Please choose a valid description</div>
                            </div>
                        </div>

                        {{-- Event location --}}
                        <div class="event__location show-appointment mt-3">
                            <label for="location">Location</label>
                            <input type="text" name="location" id="location" class="form-control">
                            <div class="invalid-feedback">The location is required</div>
                        </div>

                        {{-- Event contact person --}}
                        <div class="event__contactPerson show-appointment mt-3">
                            <label for="contact_person">Contact person</label>
                            <input type="text" name="contact_person" minlength="3" id="contact_person" class="form-control">
                            <div class="invalid-feedback">Please choose a valid contact person</div>
                            <hr>
                        </div>

                        {{-- Switch to specify end time --}}
                        <div class="custom-control custom-switch mt-4">
                            <input type="checkbox" class="custom-control-input" onchange="EventFormService.toggleDurationInputs(event)" id="duration-switch">
                            <label class="custom-control-label " for="duration-switch">Specify end</label>
                        </div>

                        {{-- Event start and end time --}}
                        <div class="form-row">
                            {{-- Event start date --}}
                            <div class="event__startDate show-task show-appointment show-medication show-questionnaire col-6">
                                <label for="start_date">From</label>
                                <input type="datetime-local" onfocus="EventFormService.saveStartDate()" name="start_date" id="start_date" class="form-control">
                                <div class="invalid-feedback">The start date is required</div>
                            </div>

                            {{-- Duration --}}
                            <div class="event__duration show-duration col-6">
                                <label for="duration_date">Until</label>
                                <input type="datetime-local" name="duration_date" id="duration_date" class="form-control">
                                <div class="invalid-feedback">The end time is required</div>
                                <div class="invalid-feedback" id="negative-duration">The duration cannot be negative</div>
                            </div>
                        </div>

                        <hr>

                        {{-- Switch to specify repetition --}}
                        <div class="custom-control custom-switch mt-4">
                            <input type="checkbox" onchange="EventFormService.toggleRepetitionInputs(event)" class="custom-control-input" id="repetition-switch">
                            <label class="custom-control-label" for="repetition-switch">Repeat</label>
                        </div>

                        <div class="form-row">
                            {{-- Event interval --}}
                            <div class="event__interval show-repetition col-6">
                                <label for="interval">Interval</label>
                                <input type="number" name="interval" id="interval" min="1" class="form-control">
                                <div class="invalid-feedback">The interval is required</div>
                            </div>

                            {{-- Event time unit --}}
                            <div class="event__timeUnit show-repetition col-6">
                                <label for="time-unit">Time unit</label>
                                <select class="form-control" name="time_unit_id" id="time-unit">
                                    <option value="">Choose a time unit</option>
                                </select>
                                <div class="invalid-feedback">The time unit is required</div>
                            </div>
                        </div>

                        {{-- Event end date --}}
                        {{-- End date for the event repetition --}}
                        <div class="event__endDate show-repetition mt-3">
                            <label for="end_date">End of repetition</label>
                            <input type="date" name="end_date" id="end_date" class="form-control">
                            <div class="invalid-feedback">The end date is required</div>
                        </div>

                        <hr>

                        {{-- Submit --}}
                        <div class="mt-4">
                            <button type="button" class="btn btn-danger" id="event-form__reset" onclick="EventFormService.resetForm()">Reset</button>
                            <button type="submit" class="btn btn-success" id="event-form__submit">Save</button>
                            <button type="button" onclick="EditEventService.editEvent()" class="btn btn-success" id="event-form__edit">Edit</button>
                            <button type="button" onclick="DeleteEventService.deleteEvent()" class="btn btn-danger" id="event-form__delete">Delete</button>
                            <button type="button" onclick="DeleteEventService.excludeEventDate()" class="btn btn-danger" id="event-form__exclude">Delete only this day</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

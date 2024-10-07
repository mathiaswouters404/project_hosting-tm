<div class="modal" id="modal-questionnaire">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"></h5>
                <button type="button" class="close" data-dismiss="modal" onclick="QuestionnaireService.closeModal()">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                    <form action="" method="post" id="questionnaireForm" novalidate class="needs-validation">
                        @method('')
                        @csrf
                        <div>
                            <h1>Title</h1>
                            <div class="form-group">
                                <input type="text" name="name" id="name"  class="form-control" placeholder="Name" minlength="3" required  value="" />
                                <div class="invalid-feedback">
                                   De naam van de questionnaire is verplicht!
                                </div>
                            </div>

                            <div>
                                <input type="text" hidden name="patient_id" id="patient_id" />
                            </div>
                        </div>

                       <div>
                           <h1>Questions</h1>
                           <div id="questionForm">
                               <div id="questions">

                               </div>
                               <a  class="btn btn-dark" onclick="QuestionService.addQuestion()">Add question</a>
                           </div>
                       </div>

                        <div>
                            <h1>Datum</h1>
                            <div>
                                {{-- Switch to specify repetition --}}
                                <div class="custom-control custom-switch mt-4">
                                    <input  type="checkbox" onchange="QuestionnaireService.toggleMode(event)" class="no-validate custom-control-input" id="repetition-switch">
                                    <label class="custom-control-label" for="repetition-switch">Repeat</label>
                                </div>

                            </div>


                                <div class="form-row">
                                    <div class="event__endDate show-repetition mt-3 w-100">
                                        <label for="end_date">Date</label>
                                        <input required type="datetime-local" name="start_date" id="start_date" class="form-control">
                                        <div class="invalid-feedback">
                                            De datum van de questionnaire is verplicht!
                                        </div>

                                    </div>

                                </div>

{{--                            repetition --}}
                           <div class="d-none" id="repetition">
                               <div class="form-row">
                                   {{-- Event interval --}}
                                   <div class="event__interval show-repetition col-6">
                                       <label for="interval">Interval</label>
                                       <input type="number" name="interval" id="interval" min="1" value="1" class="form-control">
                                   </div>

                                   {{-- Event time unit --}}
                                   <div class="event__timeUnit show-repetition col-6">
                                       <label for="time-unit">Time unit</label>
                                       <select class="form-control" name="time_unit_id" id="time-unit">
                                           <option value="">Choose a time unit</option>
                                       </select>
                                   </div>
                               </div>

                               {{-- Event end date --}}
                               {{-- End date for the event repetition --}}
                               <div class="event__endDate show-repetition mt-3">
                                   <label for="end_date">End of repetition</label>
                                   <input type="date" name="end_date" id="end_date" class="form-control">
                               </div>

                               <hr>
                           </div>
                           </div>


                        <div class="form-row mt-3">
                            <button type="submit" class="btn btn-dark" onclick="QuestionnaireService.submit()">Save questionnaire</button>

                        </div>

                </form>

            </div>
        </div>
    </div>
</div>

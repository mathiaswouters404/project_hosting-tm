<div class="modal" id="modal-log">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">log-modal</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Subtitle is show if connected to event. -->
                <h4 class="modal-subtitle"></h4>
                <form action="" method="post" id="modal-form" novalidate class="needs-validation">
                    @method('')
                    @csrf
                    <div class="form-group">
                        <label for="title">Log titel</label>
                        <input type="text" name="title" id="title"
                               class="form-control"
                               placeholder="Titel"
                               minlength="2"
                               required
                               value="">
                        <div class="invalid-feedback">
                            Vul een titel van minstens 2 karakters in alstublieft.
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="description">Log inhoud</label>
                        <textarea type="text" name="description" id="description"
                                  class="form-control"
                                  placeholder="beschrijving"
                                  minlength="2"
                                  required>
                        </textarea>
                        <div class="invalid-feedback">
                            Vul een beschrijving van minstens 2 karakters in alstublieft.
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="date">Log datum</label>
                        <input type="date" name="date" id="date"
                               class="form-control"
                               placeholder="Datum"
                               value="">
                    </div>

                    <div class="form-group">
                        <label for="visitor">Bezoeker</label>
                        <input type="text" name="visitor" id="visitor"
                               class="form-control"
                               placeholder="Bezoeker"
                               value="">
                    </div>

                    <div class="form-group">
                        @if(Auth::user()->role->id == 1)
                            <input type="hidden" name="patient_id" value="{{Auth::id()}}">
                        @else
                            <label for="patient_id">patient</label>
                            <select name="patient_id" id="patient">
                                <option value="{{$user->id}}">{{$user->firstName}} {{$user->lastName}} (deze gebruiker)</option>
                                @foreach($user->patients as $patient)
                                    <option value="{{$patient->id}}">{{$patient->firstName}} {{$patient->lastName}}</option>
                                @endforeach
                            </select>
                        @endif
                    </div>

                    <div class="form-group">
                        <label for="event_id">evenement</label>
                        <select name="event_id" id="event_id">
                            <option value="">-Geen afspraak-</option>
                            @foreach($events as $event)
                                <option value="{{$event->id}}">{{$event->name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <button id="modal-submit" type="submit" class="btn btn-success">Log opslaan</button>
                </form>
            </div>
        </div>
    </div>
</div>

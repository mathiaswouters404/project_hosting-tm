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
                <h4 class="edit-modal-subtitle"></h4>
                <form action="" method="post" id="modal-form">
                    @method('')
                    @csrf
                    <div class="form-group">
                        <label for="title">Log Titel</label>
                        <input type="text" name="title" id="title"
                               class="form-control"
                               placeholder="Titel"
                               minlength="3"
                               required
                               value="">
                    </div>

                    <div class="form-group">
                        <label for="description">Log inhoud</label>
                        <textarea type="text" name="description" id="description"
                                  class="form-control"
                                  placeholder="beschrijving"
                                  minlength="2"
                                  required>
                        </textarea>
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
                        <label for="patient">patient</label>
                        <select name="patient" id="patient">
                            @foreach($patients as $patient)
                                <option value="{{$patient->id}}">{{$patient->firstName}} {{$patient->lastName}}</option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="btn btn-success">Save log</button>
                </form>
            </div>
        </div>
    </div>
</div>

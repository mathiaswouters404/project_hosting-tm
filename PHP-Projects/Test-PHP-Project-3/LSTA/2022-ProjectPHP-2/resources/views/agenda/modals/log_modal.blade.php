<div class="modal" id="modal-log">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"></h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="" method="post" id="modal-form" onsubmit="LogService.submitLog(event)">
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
                            Vul een titel in alstublieft.
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="description">Log inhoud</label>
                        <textarea name="description" id="description" class="form-control" placeholder="beschrijving" required></textarea>
                        <div class="invalid-feedback">
                            Vul een beschrijving in alstublieft.
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
                        <label for="event_id">Evenement</label>
                        <select class="form-control" name="event_id" id="log-event-id">
                            <option value="">Selecteer een evenement</option>
                        </select>
                    </div>
                    <button id="modal-submit" type="submit" class="btn btn-success">Log opslaan</button>
                </form>
            </div>
        </div>
    </div>
</div>

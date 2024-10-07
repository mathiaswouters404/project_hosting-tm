<div class="modal" id="modal-medication">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">modal-medication-title</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="" method="post">
                    @method('')
                    @csrf
                    <div class="form-group">
                        <label for="medicatioName">Naam</label>
                        <input type="text" name="name" id="medicationName"
                               class="form-control"
                               placeholder="Naam"
                               minlength="3"
                               required
                               value="">
                    </div>
                    <div class="form-group">
                        <label for="name">Beschrijving</label>
                        <textarea rows="3" name="description" id="description"
                               class="form-control"
                               placeholder="Beschrijving"
                               minlength="3"
                               required
                                  value=""></textarea>
                    </div>
                    <button type="submit" class="btn btn-success">Bewaar medicatie</button>
                </form>
            </div>
        </div>
    </div>
</div>

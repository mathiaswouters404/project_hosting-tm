<div class="modal" id="modal-prescription">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">modal-prescription-title</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="" method="post">
                    @method('')
                    @csrf
                    <input type="hidden" id="selfPrescribed" name="selfPrescribed" value=0>
                    <div class="form-group">
                        <label for="patient">Patient</label>
                        <select class="form-control" id="patient" name="patient_id">
                            <option>Selecteer patient</option>
                        </select>
                    </div>
                    <input type="hidden" id="medication" name="medication_id">
                    <div class="form-group">
                        <label for="name">Dosis</label>
                        <input type="text" name="dosage" id="dosage"
                               class="form-control"
                               placeholder="Dosage"
                               required
                               value="">
                    </div>
                    <div class="form-group">
                        <label for="name">Reden</label>
                        <input type="text" name="reason" id="reason"
                               class="form-control"
                               placeholder="Reason"
                               minlength="3"
                               required
                               value="">
                    </div>
                    <div class="form-group">
                        <label for="startDate">Start Datum</label>
                        <input required type="date" id="startDate" name="startDate">
                    </div>
                    <div class="form-group">
                        <label for="endDate">Eind Datum</label>
                        <input type="date" id="endDate" name="endDate">
                    </div>
                    <button type="submit" class="btn btn-success">Medicatie voorschrijven</button>
                </form>
            </div>
        </div>
    </div>
</div>



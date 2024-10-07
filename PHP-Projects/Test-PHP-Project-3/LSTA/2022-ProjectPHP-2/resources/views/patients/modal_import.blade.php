<div class="modal" id="modal-import_patients">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Import patients</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="" method="post">
                    @method('')
                    @csrf
                    <div class="form-group">
                        <label for="code">Patient Code</label>
                        <input type="text" name="code" id="code"
                               class="form-control"
                               placeholder="Patient code"
                               required>
                    </div>
                    <button type="submit" class="btn btn-success">Search Patient</button>
                </form>
            </div>
        </div>
    </div>
</div>

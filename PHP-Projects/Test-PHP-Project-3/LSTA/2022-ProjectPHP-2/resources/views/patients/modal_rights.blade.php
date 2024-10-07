<div class="modal" id="modal-rights">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"></h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="" method="post">
                    @method('')
                    @csrf
                    <div>
                        <input type="hidden" name="id" id="modal-rights__id">
                    </div>

                    <div id="modal-rights__rights">
                    </div>

                    <button type="submit" class="btn btn-success">Save rights</button>
                </form>
            </div>
        </div>
    </div>
</div>

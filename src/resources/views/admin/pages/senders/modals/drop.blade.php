<!-- Modal -->
<div class="modal fade" id="dropSenderModal" tabindex="-1" role="dialog" aria-labelledby="dropSenderModalLabel" aria-hidden="true" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog" role="document">
        <div class="modal-content">

            <div class="modal-header bg-danger">
                <h5 class="modal-title text-white bold" id="dropSenderModalLabel">Ta bort avsändare</h5>
                <span style="margin-top:0.15rem;" aria-label="Close" data-dismiss="modal">
                    <i class="essential-sm essential-multiply pointer thin text-white"></i>
                </span>
            </div>

            <div class="modal-body pb-4 pt-4">
                <p>Är du säker på att du vill ta bort <b>{{ $sender->name }}</b> som avsändare?</p>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-link mr-auto" data-dismiss="modal">Nej, jag ångrade mig</button>
                <button type="button" class="btn btn-outline-danger active doDropSender" data-dismiss="modal" data-id="{{ $sender->id ?? '' }}">Ja, ta bort</button>
            </div>

        </div>
    </div>
</div>

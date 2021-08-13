<!-- Modal -->
<div class="modal fade" id="receiversModal" tabindex="-1" role="dialog" aria-labelledby="receiversModalLabel" aria-hidden="true" style="z-index: 1103;">
    <div class="modal-dialog modal-full modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header bg-dark text-white">
                <h5 class="modal-title" id="receiversModalLabel">Hantera mottagare</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <i class="essential essential-multiply"></i>
                </button>
            </div>
            <div class="modal-body">
                @include('rl_sms::admin.pages.sms.modals.receivers.receivers')
            </div>
            <div class="modal-footer">
                <div class="col-6">
                    <button type="button" class="float-left pl-0 btn btn-link" data-dismiss="modal">Stäng</button>
                    <button type="button" class="btn btn-outline-primary active float-right openImportModal">Flytta alla mottagare</button>
                </div>
                <div class="col-6">
                    <button type="button" class="btn btn-outline-danger active float-left" data-toggle="modal" data-target="#removeAllReceiversModal">Ta bort alla mottagare</button>
                    <button type="button" class="btn btn-outline-success active doUpdateReceivers float-right" data-dismiss="modal">Uppdatera</button>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Modal -->
<div class="modal fade" id="sendModal" tabindex="-1" role="dialog" aria-labelledby="sendModalLabel" aria-hidden="true" style="z-index: 1101;">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">

            <div class="modal-header bg-dark">
                <h5 class="modal-title text-white bold" id="sendModalLabel">Skicka SMS</h5>
                <span style="margin-top:0.15rem;" data-dismiss="modal" aria-label="Close">
                        <i class="essential-sm essential-multiply pointer thin text-white"></i>
                    </span>
            </div>

            <div class="modal-body" style="color: black !important;">
                <form id="send_form" method="post" action="{{ route('rl_sms.admin.sms.send') }}">

                    <!-- Choose sender -->
                    <div class="form-group">
                        <label class="bold" for="message">Avsändare</label>

                        @if(isset($senders))
                            <select class="select-sender pmd-select2 form-control" name="sender_id" style="width: 100%;">
                                <option value=""></option>
                                @foreach($senders as $sender)
                                    <option value="{{ $sender->id }}">
                                        {{ $sender->name ?? '' }}
                                    </option>
                                @endforeach
                            </select>
                        @endif
                    </div>

                    <!-- Message box/Textarea -->
                    <div class="form-group">
                        <label class="bold" for="message">Meddelande</label>
                        <textarea
                                style="max-height: 250px; min-height: 100px;"
                                name="message"
                                id="message"
                                class="form-control u-form__input"
                        ></textarea>
                    </div>

                    <span class="insert-hidden-inputs">
                        <!-- Hidden receiver inputs goes here! -->
                    </span>

                </form>

                <!-- Handle/Add receivers modal -->
                <div class="form-group">
                    <span class="btn btn-block btn-outline-primary handle-receivers" data-toggle="modal" data-target="#receiversModal">Hantera mottagare</span>
                </div>

                <!-- Receivers -->
                <h6>Antal mottagare: <span class="insert-receiver-count font-weight-normal">0</span></h6>

                <!-- Character/SMS count -->
                <h6>Antal karaktärer: <span class="char-count font-weight-normal">0</span></h6>
                <h6>Antal SMS: <span class="SMS-count font-weight-normal">0</span></h6>

            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-link mr-auto" data-dismiss="modal">Stäng</button>
                <button type="button" class="btn btn-outline-primary active doSendSMS float-right">Skicka</button>
            </div>

        </div>
    </div>
</div>


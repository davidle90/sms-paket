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

{{--                    <!-- Search/Add receivers -->--}}
{{--                    <label for="receivers" class="bold">Lägg till mottagare</label>--}}
{{--                    <div class="form-group">--}}
{{--                        <select id="receivers" class="select2-receivers" style="width:100%" multiple>--}}

{{--                        </select>--}}
{{--                    </div>--}}

{{--                    <!-- Receivers list -->--}}
{{--                    <div class="form-group">--}}
{{--                        <label class="bold">Valda mottagare</label>--}}
{{--                        <table class="table table-striped table-white table-outline table-hover mb-0 border-secondary">--}}
{{--                            <thead>--}}
{{--                                <tr>--}}
{{--                                    <th>Namn</th>--}}
{{--                                    <th>Telefonnummer</th>--}}
{{--                                    <th></th>--}}
{{--                                </tr>--}}
{{--                            </thead>--}}

{{--                            <tbody class="append-receivers">--}}

{{--                            </tbody>--}}
{{--                        </table>--}}
{{--                    </div>--}}

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

                </form>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-link mr-auto" data-dismiss="modal">Stäng</button>
                <button type="button" class="btn btn-outline-primary active doSendSMS float-right" data-dismiss="modal">Skicka</button>
            </div>

        </div>
    </div>
</div>


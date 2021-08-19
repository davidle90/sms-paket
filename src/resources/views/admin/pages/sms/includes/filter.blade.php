<div id="filter-popup-wrapper" class="card card-border-radius" style="z-index:1019;">
    <div class="card-body">

        <form name="filter-form" id="filter-form" method="get" action="{{ url()->route('rl_sms.admin.sms.filter') }}" autocomplete="off">

            <input type="hidden" name="query" value="{{ Session::get('sms_filter.query') ?? '' }}" />

            <div class="row m-1 mb-3">
                <div class="col-6 col-md-4 col-lg-3">
                    <h6 class="bold">Sortera efter</h6>
                </div>
                <div class="col-6 col-md-8 col-lg-9">

                    <div class="row">
                        <div class="col-12 col-md-6">
                            <div class="radio radio-primary">
                                <input name="order_by" id="order_by_sender_title" value="sender_title" type="radio" @if(Session::has('sms_filter.order_by') && Session::get('sms_filter.order_by') == 'sender_title') checked @endif>
                                <label for="order_by_sender_title" class="pointer"> Avsändare </label>
                            </div>
                            <div class="radio radio-primary">
                                <input name="order_by" id="order_by_receiver_title" value="receiver_title" type="radio" @if(Session::has('sms_filter.order_by') && Session::get('sms_filter.order_by') == 'receiver_title') checked @endif>
                                <label for="order_by_receiver_title" class="pointer"> Mottagare </label>
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="radio radio-primary">
                                <input name="order_by" id="order_by_country" value="country" type="radio" @if(Session::has('sms_filter.order_by') && Session::get('sms_filter.order_by') == 'country') checked @endif>
                                <label for="order_by_country" class="pointer"> Nationalitet </label>
                            </div>
                            <div class="radio radio-primary">
                                <input name="order_by" id="order_by_sent_at" value="sent_at" type="radio" @if(Session::has('sms_filter.order_by') && Session::get('sms_filter.order_by') == 'sent_at') checked @endif>
                                <label for="order_by_sent_at" class="pointer"> Skickad vid </label>
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="radio radio-primary">
                                <input name="order_by" id="order_by_created_at" value="created_at" type="radio" @if((Session::has('sms_filter.order_by') && Session::get('sms_filter.order_by') == 'created_at') || !Session::has('sms_filter.order_by')) checked @endif>
                                <label for="order_by_created_at" class="pointer"> Skapad </label>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            <div class="row m-1 mb-3">
                <div class="col-6 col-md-4 col-lg-3">
                    <h6 class="bold">Resultat</h6>
                </div>
                <div class="col-6 col-md-8 col-lg-9">
                    <div class="checkbox checkbox-primary">
                        <input name="direction" id="direction" value="asc" type="checkbox" @if(Session::has('sms_filter.direction') && Session::get('sms_filter.direction') == 'asc') checked @endif>
                        <label for="direction" class="pointer"> Visa resultat i omvänd ordning</label>
                    </div>
                </div>
            </div>

            <hr class="mb-4" />

            <!-- Datepicker -->
            <div class="row m-1">
                <div class="col-6 col-md-4 col-lg-3">
                    <h6 class="bold mt-2">Period</h6>
                </div>
                <div class="col-12 col-md-8 col-lg-9">
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="basic-addon1" style="border-top-left-radius: 4px;border-bottom-left-radius: 4px;"><i class="fal fa-calendar"></i></span>
                        </div>
                        <input 
                                type="text" 
                                name="daterange" 
                                class="datepicker-period form-control" 
                                placeholder="Dates" 
                                value="{{ session()->get('sms_filter.daterange', (isset($starts_at) && isset($starts_at)) ? $starts_at.' - '.$ends_at : '1900-01-01 - 2100-01-01') }}"
                        >
                    </div>

                </div>
            </div>

            <hr class="mb-4" />

            <div class="row m-1">
                <div class="col-6 col-md-4 col-lg-3">
                    <h6 class="bold">Antal resultat</h6>
                </div>
                <div class="col-6 col-md-8 col-lg-9">
                    <div class="radio radio-primary">
                        <input name="results_per_page" id="25_page" value="25" type="radio" @if(Session::has('sms_filter.paginate') && Session::get('sms_filter.paginate') == 25) checked @endif>
                        <label for="25_page" class="pointer"> 25 </label>
                    </div>
                    <div class="radio radio-primary">
                        <input name="results_per_page" id="50_page" value="50" type="radio" @if((Session::has('sms_filter.paginate') && Session::get('sms_filter.paginate') == 50) || !Session::has('sms_filter.paginate')) checked @endif>
                        <label for="50_page" class="pointer"> 50 </label>
                    </div>
                    <div class="radio radio-primary">
                        <input name="results_per_page" id="100_page" value="100" type="radio" @if(Session::has('sms_filter.paginate') && Session::get('sms_filter.paginate') == 100) checked @endif>
                        <label for="100_page" class="pointer"> 100 </label>
                    </div>
                    <div class="radio radio-primary">
                        <input name="results_per_page" id="250_page" value="250" type="radio" @if(Session::has('sms_filter.paginate') && Session::get('sms_filter.paginate') == 250) checked @endif>
                        <label for="250_page" class="pointer"> 250 </label>
                    </div>
                    <div class="radio radio-primary">
                        <input name="results_per_page" id="500_page" value="500" type="radio" @if(Session::has('sms_filter.paginate') && Session::get('sms_filter.paginate') == 500) checked @endif>
                        <label for="500_page" class="pointer"> 500 </label>
                    </div>
                </div>
            </div>

        </form>

    </div>
    <div class="card-footer">
        <span class="btn btn-outline-primary active pl-5 pr-5" id="search_btn"><i class="fal fa-search mr-1"></i> Sök</span>
        <span class="btn btn-link doClearFilter">Rensa filter</span>
        <div class="float-right">
            <span id="close_filter" class="btn btn-link" data-url="{{ route('rl_sms.admin.sms.clearfilter') }}">
                Stäng
            </span>
        </div>
    </div>
</div>
<div class="input-group mb-3" id="filter-search-bar-wrapper" style="z-index:1019;">
    <input name="search_input" style="border-radius:3px; z-index:1019;" type="text" class="form-control" value="{{ session()->get('sms_filter.query', '') }}" placeholder="Sök efter avsändare, mottagare eller telefonnummer" aria-describedby="search_query">
    <i class="fal fa-search text-muted" style="position:absolute; right:10px; top:10px; z-index:1019;"></i>
</div>

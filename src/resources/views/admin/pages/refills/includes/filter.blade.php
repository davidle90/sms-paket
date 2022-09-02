<div class="row">
    <div class="col-8">
        <form name="filter-form" id="filter-form" method="get" action="{{ url()->route('rl_sms.admin.refills.filter') }}" autocomplete="off">
            <!-- Datepicker -->
            <div class="input-group mb-3">
                <div class="input-group-prepend">
                    <span class="input-group-text" id="basic-addon1" style="border-top-left-radius: 4px;border-bottom-left-radius: 4px;"><i class="fal fa-calendar"></i></span>
                </div>
                <input
                    type="text"
                    name="daterange"
                    class="datepicker-period form-control"
                    placeholder="Dates"
                    value="{{ session()->get('refills_filter.daterange', (isset($starts_at) && isset($starts_at)) ? $starts_at.' - '.$ends_at : '1900-01-01 - 2100-01-01') }}"
                >
                <input
                    name="daterange_full"
                    type="hidden"
                    value="{{ session()->get('refills_filter.daterange_full', '') }}"
                >
            </div>
        </form>
    </div>

    <div class="col-4 pl-0">
        <span class="btn btn-outline-primary active" id="search_btn"><i class="fal fa-search mr-1"></i> Filtrera</span>
        <span class="btn btn-link doClearFilter">Återställ</span>
    </div>
</div>

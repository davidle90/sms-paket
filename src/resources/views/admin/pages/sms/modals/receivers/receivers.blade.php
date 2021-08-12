<div class="row">
    <div class="col-6">

        <form id="receivers_search_form" method="get" action="">

            <div class="row">
                <div class="col-6">
                    <!-- Choose $smsables/group -->
                    <div class="form-group select-sm">
                        <select name="source" class="form-control select2-source" style="width:100%;">
                            <option value=""></option>
                            @if(isset($smsables))
                                @foreach($smsables as $s)
                                    <option value="{{ $s->id }}">{{ $s->in($default_language)->label ?? $s->in($fallback_language)->label ?? '' }}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                </div>

                <div class="col-6">
                    <div class="input-group mb-3" id="filter-search-bar-wrapper">
                        <input name="search_input" style="border-radius:3px;" type="text" class="form-control" value="" placeholder="Sök mottagare" aria-describedby="search_query" disabled>
                        <i id="search_btn" class="pointer fal fa-search text-muted" style="position:absolute; right:10px; top:10px; z-index:100000;"></i>
                    </div>
                </div>
            </div>

        </form>

    </div>
</div>
<div class="row">
    <div class="col-6">
        <div class="card mb-0" style="height:calc(100vh - 241px);">
            <table class="table table-white table-outline mb-0 border-secondary table-card">
                <thead>
                    <tr>
                        <th style="min-width: 35%;">Mottagare</th>
                        <th style="width:150px;">Telefonnummer</th>
                        <th class="text-right" style="width:150px;"></th>
                    </tr>
                </thead>
            </table>
            <div id="available_receivers_wrapper" style="overflow-y: auto; height:100%;">
                <div class="text-center" style="margin-top: 37%;">
                    <i class="fal fa-spinner fa-w-16 fa-spin fa-lg"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-6">
        <div class="card mb-0" style="height:calc(100vh - 241px);">
            <table class="table table-white table-outline mb-0 border-secondary table-card">
                <thead>
                    <tr>
                        <th style="min-width: 35%;">Mottagare</th>
                        <th style="width:150px;">Telefonnummer</th>
                        <th class="text-right" style="width:150px;"></th>
                    </tr>
                </thead>
            </table>
            <div id="selected_receivers_wrapper" style="overflow-y: auto;">
                <form id="selected_receivers_form" method="post" action="{{ route('rl_campaigns.admin.campaigns.api.products.store') }}">
                    <table id="selected_receivers_table" class="table table-white table-striped table-hover mb-0 border-secondary table-borderless">
                        <tbody></tbody>
                    </table>
                </form>
            </div>
        </div>
    </div>
</div>
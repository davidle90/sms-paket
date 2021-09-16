<div class="col-2">
    <div class="card" style="border-radius:5px;">
        <div class="card-header text-center" style="border-top-right-radius: 5px; border-top-left-radius: 5px;">
            <label class="bold mb-0">Skickade meddelanden</label>
        </div>
        <div class="card-body text-center">
            <h6 class="font-weight-normal mb-0 total-sms"></h6>
        </div>
    </div>
</div>

<div class="col-2">
    <div class="card" style="border-radius:5px;">
        <div class="card-header text-center" style="border-top-right-radius: 5px; border-top-left-radius: 5px;">
            <label class="bold mb-0">SMS kvar att skicka</label>
        </div>
        <div class="card-body text-center">
            <h6 class="font-weight-normal mb-0">
                @if(!empty($latest_refill))
                    {{-- @if($latest_refill->count > 1) ({{ $latest_refill->count }}x{{ $refill_amount }}) / {{ $latest_refill->quantity + $latest_refill->remains }}  @endif --}}
                    @php
                        $treshhold_count = (isset($latest_refill->sms_unit_price)) ? floor($refill_threshold/$latest_refill->sms_unit_price) : $refill_threshold;
                        if($remaining_sms_pot <= $treshhold_count*1.5){
                            $alert = 'text-danger';
                        } elseif($remaining_sms_pot <= $treshhold_count*2){
                            $alert = 'text-warning';
                        }
                    @endphp
                    <span class="">{{ $remaining_sms_pot }} st</span>
                @else
                    0
                @endif
            </h6>
        </div>
    </div>
</div>

<div class="col-2">
    <div class="card" style="border-radius:5px;">
        <div class="card-header text-center" style="border-top-right-radius: 5px; border-top-left-radius: 5px;">
            <label class="bold mb-0">Senast påfylld (datum)</label>
        </div>
        <div class="card-body text-center">
            <h6 class="font-weight-normal mb-0">
                @if(!empty($latest_refill))
                    {{ $latest_refill->created_at->copy()->format('Y-m-d') }}
                @else
                    Ingen tidigare påfyllning
                @endif
            </h6>
        </div>
    </div>
</div>

<div class="col-2">
    <div class="card" style="border-radius:5px;">
        <div class="card-header text-center" style="border-top-right-radius: 5px; border-top-left-radius: 5px;">
            <label class="bold mb-0">Senast påfylld (summa)</label>
        </div>
        <div class="card-body text-center">
            <h6 class="font-weight-normal mb-0">
                {{ number_format($latest_refill->price_excl_vat, 0, ',', ' ') }} SEK ({{ $latest_refill->count }} st) <small>(ex.moms)</small>
            </h6>
        </div>
    </div>
</div>

<div class="col-2">
    <div class="card" style="border-radius:5px;">
        <div class="card-header text-center" style="border-top-right-radius: 5px; border-top-left-radius: 5px;">
            <label class="bold mb-0">Påfyllningssumma / tröskel</label>
        </div>
        <div class="card-body text-center">
            <h6 class="font-weight-normal mb-0">
                {!! $refill_amount ?? '0'  !!} SEK <small>(ex.moms)</small> / {{ $refill_threshold ?? '' }} SEK
            </h6>
        </div>
    </div>
</div>

<div class="col-2">
    <div class="card" style="border-radius:5px;">
        <div class="card-header text-center" style="border-top-right-radius: 5px; border-top-left-radius: 5px;">
            <label class="bold mb-0">Pris per sms</label>
        </div>
        <div class="card-body text-center">
            <h6 class="font-weight-normal mb-0">
                {{ $sms_price ?? '' }} SEK <small>(ex.moms)</small>
            </h6>
        </div>
    </div>
</div>

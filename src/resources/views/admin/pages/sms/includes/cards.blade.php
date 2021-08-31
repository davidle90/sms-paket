<div class="col-3">
    <div class="card">
        <div class="card-header">
            <label class="bold mb-0">Skickade SMS</label>
        </div>
        <div class="card-body">
            <h4 class="font-weight-normal mb-0 total-sms"></h4>
        </div>
    </div>
</div>

<div class="col-3">
    <div class="card">
        <div class="card-header">
            <label class="bold mb-0">Påfyllningsdatum</label>
        </div>
        <div class="card-body">
            <h4 class="font-weight-normal mb-0">
                @if(!empty($latest_refill))
                    {{ $latest_refill->created_at->copy()->format('Y-m-d') }}
                @else
                    <i class="text-secondary">Ingen tidigare påfyllning</i>
                @endif
            </h4>
        </div>
    </div>
</div>

<div class="col-3">
    <div class="card">
        <div class="card-header">
            <label class="bold mb-0">Påfyllningsmängd / SMS-pott</label>
        </div>
        <div class="card-body">
            <h4 class="font-weight-normal mb-0">
                @if(!empty($latest_refill))
                    {{ $latest_refill->quantity }}@if($latest_refill->count > 1) ({{ $latest_refill->count }}x{{ $refill_amount }}) / {{ $latest_refill->quantity + $latest_refill->remains }}  @endif
                @else
                    <i class="text-secondary">Ingen tidigare påfyllning</i>
                @endif
            </h4>
        </div>
    </div>
</div>

<div class="col-3">
    <div class="card">
        <div class="card-header">
            <label class="bold mb-0">Påfyllningströskel</label>
        </div>
        <div class="card-body">
            <h4 class="font-weight-normal mb-0">{{ $refill_threshold ?? '' }}</h4>
        </div>
    </div>
</div>

<div class="col-3">
    <div class="card">
        <div class="card-header">
            <label class="bold mb-0">Skickade SMS</label>
        </div>
        <div class="card-body">
            <h4 class="font-weight-normal mb-0">{{ $sms_sent ?? '' }} ({{ $messages_sent ?? '' }} meddelanden)</h4>
        </div>
    </div>
</div>

<div class="col-3">
    <div class="card">
        <div class="card-header">
            <label class="bold mb-0">Påfyllningsdatum</label>
        </div>
        <div class="card-body">
            <h4 class="font-weight-normal mb-0">{{ $latest_refill->created_at->copy()->format('Y-m-d') ?? '' }}</h4>
        </div>
    </div>
</div>

<div class="col-3">
    <div class="card">
        <div class="card-header">
            <label class="bold mb-0">Påfyllningsmängd</label>
        </div>
        <div class="card-body">
            <h4 class="font-weight-normal mb-0">{{ $latest_refill->quantity ?? '' }}@if($latest_refill->count > 1) ({{ $latest_refill->count ?? '' }}x{{ $refill_amount ?? '' }}) @endif</h4>
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


@php
    $SmsHelpers = new \Rocketlabs\Sms\App\Classes\Helpers;
@endphp

<table class="table table-striped table-white table-outline table-hover mb-0 border-secondary">
    <thead>
        <tr>
            <th>Antal påfyllningar</th>
            <th>Antal SMS</th>
            <th>Summa exkl. moms</th>
            <th>Summa inkl. moms</th>
            <th>Pris per sms</th>
            <th class="text-right">Skapad</th>
        </tr>
    </thead>

    <tbody>
        @if(isset($refills) && !$refills->isEmpty())
            @foreach($refills as $refill)
                <tr>
                    <td>{{ $refill->count ?? '' }}st</td>
                    <td>{{ $refill->quantity ?? '' }}st</td>
                    <td>{{ number_format($refill->price_excl_vat ?? 0, 2, ',', ' ') }}kr</td>
                    <td>{{ number_format($refill->price_excl_vat ?? 0, 2, ',', ' ') }}kr</td>
                    <td>{{ number_format($refill->sms_unit_price ?? 0, 2, ',', ' ') }}kr</td>
                    <td class="text-right">{{ $refill->created_at->isoFormat('D MMMM OY, HH:mm') ?? '' }}</td>
                </tr>
            @endforeach
        @endif

    </tbody>
</table>

@if(isset($refills) && !$refills->isEmpty())
    <div class="mt-3 mb-5 append-links-bot">
        {{ $refills->links() }}
    </div>
@endif

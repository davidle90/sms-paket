<table class="table table-striped table-white table-outline table-hover mb-0 border-secondary">
    <thead>
    <tr>
        <th>Avsändare</th>
        <th>Mottagare</th>
        <th>Telefonnummer</th>
        <th>Nationalitet</th>
        <th>Skickad vid</th>
        <th>Antal SMS</th>
    </tr>
    </thead>

    <tbody>

        @if(isset($sms) && !$sms->isEmpty())
            @foreach($sms as $item)
                <tr class="go-to-url pointer" data-url="{{ route('rl_sms.admin.sms.view', ['id' => $item->id]) }}">
                    <td>{{ $item->sender_title ?? '' }}</td>
                    <td>{{ $item->receiver_title ?? '' }}</td>
                    <td>{{ $item->receiver_phone ?? '' }}</td>
                    <td>
                        <span
                                class="flag-icon flag-icon-{{ $item->country ?? '' }} mr-1"
                                data-toggle="tooltip"
                                data-placement="top"
                                title="{{ country($item->country)->getName() ?? '' }}"
                        ></span>
{{--                        {{ country($item->country)->getName() ?? '' }}--}}
                    </td>
                    <td>{{ $item->sent_at->copy()->isoFormat('D MMMM OY, HH:MM') ?? '' }}</td>
                    <td>{{ $item->quantity ?? '' }}</td>
                </tr>
            @endforeach
        @endif

    </tbody>
</table>

@if(isset($sms) && !$sms->isEmpty())
    <div class="hidden links-grab">
        <span class="float-right">{{ $sms->links('rl_sms::admin.pages.sms.includes.pagination') }}</span>
    </div>

    <div class="mt-3 mb-5 append-links-bot">
        {{ $sms->links() }}
    </div>
@endif

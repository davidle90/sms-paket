@if(isset($sms) && !$sms->isEmpty())
    <table class="table table-striped table-white table-outline table-hover mb-0 border-secondary">
        <thead>
        <tr>
            <th>Avsändare titel</th>
            <th>Telefonnummer</th>
            <th>Mottagare titel</th>
            <th>Telefonnummer</th>
            <th>Nationalitet</th>
            <th>Skickad vid</th>
            <th>Antal</th>
        </tr>
        </thead>

        <tbody>
            @foreach($sms as $item)
                <tr class="go-to-url pointer" data-url="{{ route('rl_sms.admin.sms.view', ['id' => $item->id]) }}">
                    <td>{{ $item->sender_title ?? '' }}</td>
                    <td>{{ $item->sender_phone ?? '' }}</td>
                    <td>{{ $item->receiver_title ?? '' }}</td>
                    <td>{{ $item->receiver_phone ?? '' }}</td>
                    <td>{{ $item->country ?? '' }}</td>
                    <td>{{ $item->sent_at ?? '' }}</td>
                    <td>{{ $item->quantity ?? '' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="mt-3 mb-5">
        {{ $sms->links() }}
    </div>

    <span class="hidden links-grab">
        <span class="float-right">{{ $sms->links('rl_sms::admin.pages.sms.includes.pagination') }}</span>
    </span>
@endif

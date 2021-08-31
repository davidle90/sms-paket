
@php
    $SmsHelpers = new \Rocketlabs\Sms\App\Classes\Helpers;
@endphp

<table class="table table-striped table-white table-outline table-hover mb-0 border-secondary">
    <thead>
    <tr>
        <th>Avsändare</th>
        <th>Mottagare</th>
        <th>Telefonnummer</th>
        <th>Nationalitet</th>
        <th>Skickat vid</th>
        <th>Antal SMS</th>
        <th>Status</th>
    </tr>
    </thead>

    <tbody>

        @if(isset($sms) && !$sms->isEmpty())
            @foreach($sms as $item)
                <tr class="go-to-url pointer" data-url="{{ route('rl_sms.admin.sms.view', ['id' => $item->id]) }}">
                    <td>
                        @if(isset($item->sender_title) && !empty($item->sender_title))
                            {{ $item->sender_title }}
                        @else
                            <i class="text-secondary">Ej angivet</i>
                        @endif
                    </td>
                    <td>
                        @if(isset($item->receiver_title) && !empty($item->receiver_title))
                            {{ $item->receiver_title }}
                        @else
                            <i class="text-secondary">Ej angivet</i>
                        @endif
                    </td>
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
                    <td>{{ $item->sent_at->copy()->isoFormat('D MMMM OY, HH:mm') ?? '' }}</td>
                    <td>{{ $item->quantity ?? '' }}</td>

                    @php
                        $color_class            = '';
                        $status_text            = 'Skickat';
                        $failed_count           = 0;
                        $verify_count           = 0;
                        $status_exists          = false;
                        $status_exists_verify   = false;

                        foreach($item->nexmo as $n) {

                            if(isset($n->request_id) && isset($n->status)) {

                                $status_exists_verify = true;

                                switch (rl_sms::getVerifyStatusString($n->status)) {
                                    case $SmsHelpers::VERIFY_SUCCESS:
                                        $verify_count++;
                                        break;
                                    default:
                                        break;
                                }

                            } elseif(isset($n->receipt->status)) {

                                $status_exists = true;

                                switch ($n->receipt->status) {
                                    case 'delivered':
                                        break;
                                    default:
                                        $failed_count++;
                                        break;
                                }

                            }
                        }

                        if($status_exists_verify) {

                            switch ($verify_count) {
                                case 0:
                                    $color_class = 'text-danger';
                                    $status_text = 'Ej verifierad';
                                    break;
                                default:
                                    $color_class = 'text-success';
                                    $status_text = 'Verifierad';
                                    break;
                            }

                        } elseif($status_exists) {
                            switch ($failed_count) {
                                case 0:
                                    $color_class = 'text-success';
                                    $status_text = 'Levererat';
                                    break;
                                default:
                                    $color_class = 'text-warning';
                                    $status_text = 'Anmärkning';
                                    break;
                            }
                        }
                    @endphp

                    <td class="{{ $color_class }}">{{ $status_text }}</td>

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

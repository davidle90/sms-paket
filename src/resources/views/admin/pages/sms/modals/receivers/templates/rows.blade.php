
@if(isset($receivers) && !empty($receivers))
    @foreach($receivers as $key => $receiver)

        @php
            $id = str_replace('\\', '_', get_class($receiver)).'_'.$receiver->id.'_'.($receiver->receiver_phone_label ?? '');
        @endphp

        @if(!in_array($id, $selected_ids))

            <tr
                    id="selected_receivers_row_{{ $id }}"
            >
                <input type="hidden" name="selected_ids[]" value="{{ $id }}" />
                <input type="hidden" name="receivers[{{ $key }}][name]" value="{{ $receiver->receiver_name ?? '' }}">
                <input type="hidden" name="receivers[{{ $key }}][phone]" value="{{ $receiver->receiver_phone ?? '' }}">

                <td class="truncate" style="min-width: 35%;">
                    {{ $receiver->receiver_name ?? '' }}
                </td>
                <td style="width:150px;">{{ $receiver->receiver_phone ?? '' }}</td>
                <td class="text-right" style="width:150px;">
                    <span class="text-link text-danger remove_receiver" data-id="{{ $receiver->id }}">
                        Ta bort mottagare
                    </span>
                </td>
            </tr>

        @endif
    @endforeach
@endif

@if(isset($receivers) && !empty($receivers))
    @foreach($receivers as $key => $receiver)
        @if(!in_array(str_replace('\\', '_', get_class($receiver)).'_'.$receiver->id, $selected_ids))

            <tr
                    id="selected_receivers_row_{{ str_replace('\\', '_', get_class($receiver)).'_'.$receiver->id }}"
            >
                <input type="hidden" name="selected_ids[]" value="{{ str_replace('\\', '_', get_class($receiver)).'_'.$receiver->id }}" />
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
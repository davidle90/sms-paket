<table class="table table-white table-striped table-hover mb-0 border-secondary table-borderless available_receivers_table">
    <tbody class="append-receivers-to">
        @if(isset($receivers) && !empty($receivers))
            @foreach($receivers as $key => $receiver)

                @php
                    $id = str_replace('\\', '_', get_class($receiver)).'_'.$receiver->id.'_'.($receiver->receiver_phone_label ?? '');
                    $is_selected = in_array($id, $selected_ids);
                @endphp

                <tr
                        id="available_receivers_row_{{ $id }}"
                        @if($is_selected)
                            class="text-secondary"
                        @endif
                >
                    <input type="hidden" name="selected_ids[]" value="{{ $id }}" />
                    <input type="hidden" name="receivers[{{ $key }}][name]" value="{{ $receiver->receiver_name ?? '' }}">
                    <input type="hidden" name="receivers[{{ $key }}][phone]" value="{{ $receiver->receiver_phone ?? '' }}">

                    <td class="truncate" style="min-width: 35%;">
                        {{ $receiver->receiver_name ?? '' }}
                    </td>
                    <td style="width:150px;">{{ $receiver->receiver_phone ?? '' }}</td>
                    <td class="text-right" style="width:150px;">
                        <span class="@if($is_selected) text-secondary added @else text-link add_receiver @endif" data-id="{{ $receiver->id }}">
                            @if($is_selected) Tillagd @else Lägg till mottagare @endif
                        </span>
                    </td>
                </tr>
            @endforeach
        @endif
    </tbody>
</table>

@if(isset($receivers) && !empty($receivers))
    {{ $receivers->links() }}
@endif





<table id="available_receivers_table" class="table table-white table-striped table-hover mb-0 border-secondary table-borderless">
    <tbody>
    @if(isset($receivers) && !empty($receivers))
        @foreach($receivers as $receiver)

            @php
                $is_selected = in_array(str_replace('\\', '_', get_class($receiver)).'_'.$receiver->id, $selected_ids);
            @endphp

            <tr
                    id="available_receivers_row_{{ str_replace('\\', '_', get_class($receiver)).'_'.$receiver->id }}"
                    @if($is_selected)
                        class="text-secondary"
                    @endif
            >
                <input type="hidden" name="receivers[]" value="{{ str_replace('\\', '_', get_class($receiver)).'_'.$receiver->id }}" />
                <td class="truncate" style="min-width: 35%;">
                    NAMN/TITEL
                </td>
                <td style="width:150px;">{{ $receiver->{$number_key} ?? '' }}</td>
                <td class="text-right" style="width:150px;">
                    <span class="@if($is_selected) text-secondary added @else text-link add_receiver @endif" data-id="{{ $receiver->id }}">
                        @if($is_selected)) Tillagd @else Lägg till mottagare @endif
                    </span>
                </td>
            </tr>
        @endforeach
    @endif
    </tbody>
</table>
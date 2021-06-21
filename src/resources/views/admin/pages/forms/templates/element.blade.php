<div class="row" id="section_{{ $section_index }}_element_{{ $element_index }}">
    <input
            type="hidden"
            min=1
            class="sortOrderUpdateElementVal"
            value="{{ $sort_order ?? '' }}"
            name="sections[{{ $section_index }}][elements][{{ $element_index }}][sort_order]"
            id="sections_{{ $section_index }}_elements_{{ $element_index }}_sort_order"
    >
    <input type="hidden" name="sections[{{ $section_index }}][elements][{{ $element_index }}][type_id]" value="{{ $type_id }}" class="element-type-id">
    <input
            type="hidden"
            class="element-id"
            value=""
            name="sections[{{ $section_index }}][elements][{{ $element_index }}][id]"
    >

    @include('rl_forms::admin.pages.forms.modals.element')

    <div class="col-12">
        <div class="card">

            <div class="card-header handle-element" style="background-color: #dcefdc; cursor: grabbing;">
                <b>Fråga <span class="sortOrderUpdateElementLabel">{{ $sort_order ?? '' }}</span> - {{ $type_label ?? '' }}</b>

                <span class="float-right">
                    <span
                            class="m-0 pointer element-modal-button"
                            data-toggle="modal"
                            data-target="#elementEditModal_section_{{ $section_index }}_element_{{ $element_index }}"
                            data-section-index="{{ $section_index }}"
                            data-element-index="{{ $element_index }}"
                    >
                        <i class="fal fa-pencil-alt"></i>
                    </span>
                </span>
            </div>

            <div class="card-body update-card-body">

            </div>

        </div>
    </div>
</div>

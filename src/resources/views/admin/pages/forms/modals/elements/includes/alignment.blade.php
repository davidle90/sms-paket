<h6>
    <span class="bold">Placering</span>
</h6>

<div class="row mb-3">
    <div class="col-3">
        <div class="custom-control custom-radio element-modal-alignment element-modal-alignment-vertical">
            <input
                    type="radio"
                    class="custom-control-input pointer"
                    value="vertical"
                    id="section_{{ $section_index }}_element_{{ $element_index }}_aligment_vertical"
                    name="sections[{{ $section_index }}][elements][{{ $element_index }}][alignment]"
                    @if((isset($element->alignment) && $element->alignment === 'vertical') || !isset($element->alignment)) checked="checked" @endif
            >
            <label class="custom-control-label pointer" for="section_{{ $section_index }}_element_{{ $element_index }}_aligment_vertical" style="margin-top: 3px;">
                Vertikalt
            </label>
        </div>
    </div>
    <div class="col-3">
        <div class="custom-control custom-radio element-modal-alignment element-modal-alignment-horizontal">
            <input
                    type="radio"
                    class="custom-control-input pointer"
                    value="horizontal"
                    id="section_{{ $section_index }}_element_{{ $element_index }}_aligment_horizontal"
                    name="sections[{{ $section_index }}][elements][{{ $element_index }}][alignment]"
                    @if(isset($element->alignment) && $element->alignment === 'horizontal') checked="checked" @endif
            >
            <label class="custom-control-label pointer" for="section_{{ $section_index }}_element_{{ $element_index }}_aligment_horizontal" style="margin-top: 3px;">
                Horisontellt
            </label>
        </div>
    </div>
</div>



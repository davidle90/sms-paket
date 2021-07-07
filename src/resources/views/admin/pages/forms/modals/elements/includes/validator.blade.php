<h6>
    <span class="bold">Validering</span>
</h6>

<div class="row">
    <div class="col-12">
        <div class="mb-3 form-group element-modal-validator">
            <input
                    type="text"
                    name="sections[{{ $section_index }}][elements][{{ $element_index }}][validator]"
                    id="section_{{ $section_index }}_element_{{ $element_index }}_validator"
                    class="form-control"
                    value="{{ (isset($element)) ? $element->validator ?? '' : '' }}"
            >
        </div>
    </div>
</div>



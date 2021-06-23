<h6>
    <span class="bold element-slug-label">Slug</span>
</h6>

<div class="row">
    <div class="col-12">
        <div class="mb-3 form-label-group form-group element-modal-slug">
            <input
                    type="text"
                    name="sections[{{ $section_index }}][elements][{{ $element_index }}][slug]"
                    id="section_{{ $section_index }}_element_{{ $element_index }}_slug"
                    class="form-control element-slug"
                    value="{{ (isset($element)) ? $element->slug ?? '' : '' }}"
            >
            <label for="section_{{ $section_index }}_element_{{ $element_index }}_slug">
                Slug <i class="fa fa-asterisk required-marker" aria-hidden="true"></i>
            </label>
        </div>
    </div>
</div>



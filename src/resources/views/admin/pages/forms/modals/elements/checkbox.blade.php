<div class="row">
    <div class=" col-12">
        <div class="card">
            <div class="card-body" style="background-color: #e9f3fc; color: #008FFA;">
                <p>
                    Checkbox även kallat för kryssrutor är ett multisvars alterativ.
                </p>
            </div>
        </div>
    </div>
</div>

<h6 class="bold">Label</h6>

@foreach($languages as $key => $lang)
    <!-- Label -->
    <div class="row">
        <div class="col-12">
            <div class="mb-3 form-label-group form-group">
                <input
                        type="text"
                        name="sections[{{ $section_index }}][elements][{{ $element_index ?? '' }}][labels][{{ $key }}]"
                        id="section_{{ $section_index }}_element_{{ $element_index ?? '' }}_label_{{ $key }}"
                        class="form-control"
                        value="{{ (isset($element)) ? $element->in($key)->label : '' }}"
                >
                <label for="section_{{ $section_index }}_element_{{ $element_index ?? '' }}_label_{{ $key }}">
                    @ucfirst(language($key)->getNativeName()) ({{ language($key)->getName() }})
                    @if($key == $default_language)
                        <i class="fa fa-asterisk required-marker" aria-hidden="true"></i>
                    @endif
                </label>
            </div>
        </div>
    </div>
@endforeach

<h6 class="bold">Beskrivning</h6>

@foreach($languages as $key => $lang)
    <!-- Description -->
    <div class="row">
        <div class="col-12">
            <small>
                @ucfirst(language($key)->getNativeName()) ({{ language($key)->getName() }})
                @if($key == $default_language)
                    <i class="fa fa-asterisk required-marker" aria-hidden="true"></i>
                @endif
            </small>
            <div class="mb-3 form-group create-element-modal-textareas">
                <input type="hidden" value="{{ $key }}">
                <textarea
                        name="sections[{{ $section_index }}][elements][{{ $element_index ?? 'create' }}][descriptions][{{ $key }}]"
                        id="section_{{ $section_index }}_element_{{ $element_index ?? 'create' }}_description_{{ $key }}"
                        class="{{ (isset($element)) ? 'redactor-'.$key : '' }} form-control u-form__input"
                >
                    {{ (isset($element)) ? $element->in($key)->description : '' }}
                </textarea>
            </div>
        </div>
    </div>
@endforeach
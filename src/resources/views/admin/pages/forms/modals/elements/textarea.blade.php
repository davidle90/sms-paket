<div class="row">
    <div class=" col-12">
        <div class="card">
            <div class="card-body" style="background-color: #e9f3fc; color: #008FFA;">
                Textarea även kallat textfält, en längre text som fylls i av användaren.
            </div>
        </div>
    </div>
</div>

<h6>
    <span class="bold">Label</span>
    <span
            class="text-link float-right edit-translation text-normal"
            data-target="label"
            data-mode="show"
            data-section-index="{{ $section_index }}"
            data-element-index="{{ $element_index }}"
    >
        Redigera språk
    </span>
</h6>

<div class="label-wrapper">
@foreach($languages as $key => $lang)
    <!-- Label -->
        <div class="row">
            <div class="col-12">
                <div class="mb-3 form-label-group form-group element-modal-labels @if($key !== $default_language) translation @endif" @if($key !== $default_language) style="display: none" @endif>
                    <input class="element-modal-labels-iso" type="hidden" value="{{ $key }}">
                    <input
                            type="text"
                            name="sections[{{ $section_index }}][elements][{{ $element_index }}][labels][{{ $key }}]"
                            id="section_{{ $section_index }}_element_{{ $element_index }}_label_{{ $key }}"
                            class="form-control element-modal-labels-input"
                            value="{{ (isset($element)) ? $element->in($key)->label ?? '' : '' }}"
                    >
                    <label for="section_{{ $section_index }}_element_{{ $element_index }}_label_{{ $key }}">
                        @ucfirst(language($key)->getNativeName()) ({{ language($key)->getName() }})
                    </label>
                </div>
            </div>
        </div>
    @endforeach
</div>

<!-- Slug -->
@include('rl_forms::admin.pages.forms.modals.elements.includes.slug')

<h6>
    <span class="bold">Beskrivning</span>
    <span
            class="text-link float-right edit-translation text-normal"
            data-target="description"
            data-mode="show"
            data-section-index="{{ $section_index }}"
            data-element-index="{{ $element_index }}"
    >
        Redigera språk
    </span>
</h6>

<div class="description-wrapper">
@foreach($languages as $key => $lang)
    <!-- Description -->
        <div class="row @if($key !== $default_language) translation @endif" @if($key !== $default_language) style="display: none" @endif>
            <div class="col-12">
                <small>
                    @ucfirst(language($key)->getNativeName()) ({{ language($key)->getName() }})
                </small>
                <div class="mb-3 form-group element-modal-textareas">
                    <input type="hidden" value="{{ $key }}">
                    <textarea
                            name="sections[{{ $section_index }}][elements][{{ $element_index }}][descriptions][{{ $key }}]"
                            id="section_{{ $section_index }}_element_{{ $element_index }}_description_{{ $key }}"
                            class="{{ (isset($element)) ? 'redactor-'.$key : '' }} form-control u-form__input"
                    >
                    {{ (isset($element)) ? $element->in($key)->description ?? '' : '' }}
                </textarea>
                </div>
            </div>
        </div>
    @endforeach
</div>

<!-- Validator -->
@include('rl_forms::admin.pages.forms.modals.elements.includes.validator')

<!-- Required checkbox -->
<div class="row">
    <div class="col-12">
        <div class="custom-control custom-checkbox d-flex align-items-center mb-3 element-modal-required-checkbox">
            <input name="sections[{{ $section_index }}][elements][{{ $element_index }}][required]"
                   type="checkbox"
                   class="custom-control-input"
                   id="section_{{ $section_index }}_element_{{ $element_index }}_required"
                   @if(isset($element) && $element->pivot->required == 1) checked @endif
                   value="1" >
            <label class="custom-control-label pointer" for="section_{{ $section_index }}_element_{{ $element_index }}_required">
                Frågan är ett krav och måste besvaras
            </label>
        </div>
    </div>
</div>

<h6>
    <span class="bold">Krav text</span>
    <span
            class="text-link float-right edit-translation text-normal"
            data-target="required"
            data-mode="show"
            data-section-index="{{ $section_index }}"
            data-element-index="{{ $element_index }}"
    >
        Redigera språk
    </span>
</h6>

<div class="required-wrapper">
@foreach($languages as $key => $lang)
    <!-- Required text -->
        <div class="row">
            <div class="col-12">
                <div class="mb-3 form-label-group form-group element-modal-required-text @if($key !== $default_language) translation @endif" @if($key !== $default_language) style="display: none" @endif>
                    <input type="hidden" class="element-modal-required-text-iso" value="{{ $key }}">
                    <input
                            type="text"
                            name="sections[{{ $section_index }}][elements][{{ $element_index }}][required_texts][{{ $key }}]"
                            id="section_{{ $section_index }}_element_{{ $element_index }}_required_text_{{ $key }}"
                            class="form-control element-modal-required-text-input"
                            value="{{ (isset($element)) ? $element->in($key)->required ?? '' : '' }}"
                    >
                    <label for="section_{{ $section_index }}_element_{{ $element_index }}_required_text_{{ $key }}">
                        @ucfirst(language($key)->getNativeName()) ({{ language($key)->getName() }})
                    </label>
                </div>
            </div>
        </div>
    @endforeach
</div>

<!-- Column size, collapsable -->
@include('rl_forms::admin.pages.forms.modals.elements.includes.size')

<style>
    .select2-selection__clear {
        margin-top: 0px;
    }
</style>

@if(!isset($template) || $template === false)
    @push('scripts')
@endif

<script type="text/javascript">
    $(document).ready(function(){

        $('.edit-translation').off('click').on('click', function(){
            let target          = $(this).attr('data-target');
            let mode            = $(this).attr('data-mode');
            let section_index   = $(this).attr('data-section-index');
            let element_index   = $(this).attr('data-element-index');

            $(`#elementEditModal_section_${ section_index }_element_${ element_index } .${ target }-wrapper`).find('.translation').each(function(){
                if(mode === 'hide') {
                    $(this).hide();
                } else {
                    $(this).show();
                }
            });

            if(mode === 'show') {
                $(this).attr('data-mode', 'hide');
                $(this).text('Dölj språk');
            } else {
                $(this).attr('data-mode', 'show');
                $(this).text('Redigera språk');
            }
        });

    });
</script>

@if(!isset($template) || $template === false)
    @endpush
@endif


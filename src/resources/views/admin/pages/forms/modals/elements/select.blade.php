<div class="row">
    <div class=" col-12">
        <div class="card">
            <div class="card-body" style="background-color: #e9f3fc; color: #008FFA;">
                Dropdown även kallat rullgardinsmeny, en lista av val.
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

<h6 class="bold">Importera tabell</h6>

<div class="row">
    <div class="col-12">
        <div class="form-group element-modal-table">
            <select
                    id="section_{{ $section_index }}_element_{{ $element_index }}_table"
                    class="select-table form-control"
                    aria-labelledby="labelTable_create"
                    name="sections[{{ $section_index }}][elements][{{ $element_index }}][table]"
                    style="width:100%;"
            >
                <option value=""></option>
                @foreach($tables as $table)
                    @if(isset($element->table_id) && $element->table_id == $table->id)
                        <option value="{{ $table->id }}" selected>{{ $table->label ?? '' }}</option>
                    @else
                        <option value="{{ $table->id }}">{{ $table->label ?? '' }}</option>
                    @endif
                @endforeach
            </select>
        </div>
    </div>
</div>

<h6>
    <span class="bold">Svarsalternativ</span>
    <span
            class="text-link float-right edit-translation option-translation text-normal"
            data-target="checkbox"
            data-mode="show"
            data-section-index="{{ $section_index }}"
            data-element-index="{{ $element_index }}"
    >
        Redigera språk
    </span>
</h6>

<!-- Options wrapper -->
<span class="append-options-to checkbox-wrapper mt-3">
    @if(isset($element->options) && !empty($element->options))
        @foreach($element->options as $index => $option)
            <div id="elementEditModal_section_{{ $section_index }}_element_{{ $element_index }}_option_{{ $index }}">
                <input type="hidden" name="sections[{{ $section_index }}][elements][{{ $element_index }}][options][{{ $index }}][id]" value="{{ $option->id }}" class="option-id">

                <small>Svarsalternativ #<span class="option-label">{{ $index + 1 }}</span></small>
                @foreach($languages as $key => $lang)
                        <!-- Checkbox - Option -->
                        <div class="row">
                        <div class="col-12 element-modal-options @if($key !== $default_language) translation @endif" @if($key !== $default_language) style="display: none" @endif>
                            <div class="mb-3 form-label-group form-group">
                                <input class="checkbox-iso" type="hidden" value="{{ $key }}">
                                <input
                                        type="text"
                                        name="sections[{{ $section_index }}][elements][{{ $element_index }}][options][{{ $index }}][labels][{{ $key }}]"
                                        id="section_{{ $section_index }}_element_{{ $element_index }}_option_{{ $index }}_{{ $key }}"
                                        class="form-control checkbox-input checkbox-in-{{ $key }}"
                                        value="{{ $option->in($key)->label ?? '' }}"
                                >
                                <label for="section_{{ $section_index }}_element_{{ $element_index }}_option_{{ $index }}_{{ $key }}">
                                    @ucfirst(language($key)->getNativeName()) ({{ language($key)->getName() }})
                                    @if($key == $default_language)
                                        <i class="fa fa-asterisk required-marker" aria-hidden="true"></i>
                                    @endif
                                </label>
                                @if($key == $default_language)
                                    <span
                                            class="pointer doRemoveOption"
                                            style="position: absolute; right: 13px; top: 13px;"
                                            data-option-id="elementEditModal_section_{{ $section_index }}_element_{{ $element_index }}_option_{{ $index }}"
                                            data-section-index="{{ $section_index }}"
                                            data-element-index="{{ $element_index }}"
                                    >
                                        <i class="fal fa-times text-danger"></i>
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endforeach
    @endif
</span>

<!-- Add button for options -->
<div class="row">
    <div class="col-12 col-md-6">
        <span class="doAddOption btn btn-block btn-outline-primary" data-section-index="{{ $section_index }}" data-element-index="{{ $element_index }}">
            <i class="essential-xs essential-add"></i> Lägg till svarsalternativ
        </span>
    </div>
</div>

<!-- Required checkbox -->
<div class="row mt-3">
    <div class="col-12">
        <div class="custom-control custom-checkbox d-flex align-items-center mb-2 element-modal-required-checkbox">
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

<!-- Column size, collapsable -->
@include('rl_forms::admin.pages.forms.modals.elements.includes.size')

@if(!isset($template) || $template === false)
    @push('scripts')
@endif

<script type="text/javascript">
    $(document).ready(function(){

        $('.select-table').select2({
            placeholder: "Välj tabell",
            allowClear: true,
            minimumResultsForSearch: -1
        });

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

        $('.doAddOption').off('click').on('click', function(){
            let $template       = $('#option_template').clone();
            let section_index   = $(this).attr('data-section-index');
            let element_index   = $(this).attr('data-element-index');
            let $container      = $(`#elementEditModal_section_${ section_index }_element_${ element_index } .append-options-to`);
            let count           = $container.children('div').length;

            $template.find('.option-id').attr('name', `sections[${ section_index }][elements][${ element_index }][options][${ count }][id]`);
            $template.attr('id', `elementEditModal_section_${ section_index }_element_${ element_index }_option_${ count }`);
            $template.removeClass('hidden');
            $template.find('.col-12').addClass('element-modal-options');
            $template.find('.doRemoveOption').attr('data-option-id', `elementEditModal_section_${ section_index }_element_${ element_index }_option_${ count }`);
            $template.children().each(function(){
                let iso = $(this).find('.checkbox-iso').val();

                $(this).find('.checkbox-input').attr('name', `sections[${ section_index }][elements][${ element_index }][options][${ count }][labels][${ iso }]`);
                $(this).find('.checkbox-input').attr('id', `section_${ section_index }_element_${ element_index }_option_${ count }_${ iso }`);
                $(this).find('label').attr('for', `section_${ section_index }_element_${ element_index }_option_${ count }_${ iso }`);
                $(this).find('.option-label').text(count + 1);

                $(this).find('.checkbox-input').on('input', function() {
                    let $field = $(this).closest('.form-label-group');

                    if (this.value) {
                        $field.addClass('field--not-empty');
                        $field.removeClass('field--empty');
                    } else {
                        $field.removeClass('field--not-empty');
                        $field.addClass('field--empty');
                    }
                });

                if($(`#elementEditModal_section_${ section_index }_element_${ element_index } .option-translation`).attr('data-mode') === 'hide') {
                    $(this).find('.translation').show();
                }
            });

            $container.append($template);

            $(`#elementEditModal_section_${ section_index }_element_${ element_index }_body`).scrollTop($(`#elementEditModal_section_${ section_index }_element_${ element_index }_body`)[0].scrollHeight);
        });

        $(document).on('click', '.doRemoveOption', function(){
            let section_index   = $(this).attr('data-section-index');
            let element_index   = $(this).attr('data-element-index');
            let option_id       = $(this).attr('data-option-id');

            $(`#${ option_id }`).remove();

            let count       = 0;
            let $container  = $(`#elementEditModal_section_${ section_index }_element_${ element_index } .append-options-to`);

            $container.children('div').each(function(){
                $(this).find('.option-label').text(count + 1);

                $(this).children('div').each(function(){
                    let iso = $(this).find('.checkbox-iso').val();
                    $(this).find('.checkbox-input').attr('name', `sections[${ section_index }][elements][${ element_index }][options][${ count }][labels][${ iso }]`);
                    $(this).find('.checkbox-input').attr('id', `section_${ section_index }_element_${ element_index }_option_${ count }_${ iso }`);
                    $(this).find('label').attr('for', `section_${ section_index }_element_${ element_index }_option_${ count }_${ iso }`);
                })

                count++;
            });
        });

    });
</script>

@if(!isset($template) || $template === false)
    @endpush
@endif

<style>
    .select2-selection__clear {
        margin-top: 0px;
    }
</style>

<!-- Templates -->
<div id="option_template" class="hidden">
    <input type="hidden" name="" value="" class="option-id">

    <small>Svarsalternativ #<span class="option-label">99</span></small>
@foreach($languages as $key => $lang)
    <!-- Checkbox - Option -->
        <div class="row">
            <div class="col-12 @if($key !== $default_language) translation @endif" @if($key !== $default_language) style="display: none" @endif>
                <div class="mb-3 form-label-group form-group">
                    <input class="checkbox-iso" type="hidden" value="{{ $key }}">
                    <input
                            type="text"
                            name=""
                            id="section_{{ $section_index }}_element_{{ $element_index }}_option_template_{{ $key }}"
                            class="form-control checkbox-input checkbox-in-{{ $key }}"
                            value=""
                    >
                    <label for="section_{{ $section_index }}_element_{{ $element_index }}_option_template_{{ $key }}">
                        @ucfirst(language($key)->getNativeName()) ({{ language($key)->getName() }})
                        @if($key == $default_language)
                            <i class="fa fa-asterisk required-marker" aria-hidden="true"></i>
                        @endif
                    </label>
                    @if($key == $default_language)
                        <span
                                class="pointer doRemoveOption"
                                style="position: absolute; right: 13px; top: 13px;"
                                data-option-id=""
                                data-section-index="{{ $section_index }}"
                                data-element-index="{{ $element_index }}"
                        >
                            <i class="fal fa-times text-danger"></i>
                        </span>
                    @endif
                </div>
            </div>
        </div>
    @endforeach
</div>




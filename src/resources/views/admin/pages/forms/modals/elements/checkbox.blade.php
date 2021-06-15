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

<h6><span class="bold">Label</span><span class="text-link float-right edit-translation text-normal" data-target="label" data-mode="show">Redigera språk</span></h6>

<div class="label-wrapper">
    @foreach($languages as $key => $lang)
        <!-- Label -->
        <div class="row">
            <div class="col-12">
                <div class="mb-3 form-label-group form-group @if($key !== $default_language) translation @endif" @if($key !== $default_language) style="display: none" @endif>
                    <input
                            type="text"
                            name="sections[{{ $section_index }}][elements][{{ $element_index ?? 'create' }}][labels][{{ $key }}]"
                            id="section_{{ $section_index }}_element_{{ $element_index ?? 'create' }}_label_{{ $key }}"
                            class="form-control"
                            value="{{ (isset($element)) ? $element->in($key)->label : '' }}"
                    >
                    <label for="section_{{ $section_index }}_element_{{ $element_index ?? '' }}_label_{{ $key }}">
                        @ucfirst(language($key)->getNativeName()) ({{ language($key)->getName() }})
                    </label>
                </div>
            </div>
        </div>
    @endforeach
</div>

<h6><span class="bold">Beskrivning</span><span class="text-link float-right edit-translation text-normal" data-target="description" data-mode="show">Redigera språk</span></h6>

<div class="description-wrapper">
    @foreach($languages as $key => $lang)
        <!-- Description -->
        <div class="row @if($key !== $default_language) translation @endif" @if($key !== $default_language) style="display: none" @endif>
            <div class="col-12">
                <small>
                    @ucfirst(language($key)->getNativeName()) ({{ language($key)->getName() }})
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
</div>

<h6><span class="bold">Krav text</span><span class="text-link float-right edit-translation text-normal" data-target="required" data-mode="show">Redigera språk</span></h6>

<div class="required-wrapper">
@foreach($languages as $key => $lang)
    <!-- Required text -->
        <div class="row">
            <div class="col-12">
                <div class="mb-3 form-label-group form-group @if($key !== $default_language) translation @endif" @if($key !== $default_language) style="display: none" @endif>
                    <input
                            type="text"
                            name="sections[{{ $section_index }}][elements][{{ $element_index ?? 'create' }}][required_texts][{{ $key }}]"
                            id="section_{{ $section_index }}_element_{{ $element_index ?? 'create' }}_required_text_{{ $key }}"
                            class="form-control"
                            value="{{ (isset($element)) ? $element->in($key)->label : '' }}"
                    >
                    <label for="section_{{ $section_index }}_element_{{ $element_index ?? 'create' }}_required_text_{{ $key }}">
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
        <div class="pmd-textfield pmd-textfield-floating-label form-group">
            <select
                id="section_{{ $section_index }}_element_{{ $element_index ?? 'create' }}_table"
                class="select-table pmd-select2 form-control"
                aria-labelledby="labelTable_create"
                name="sections[{{ $section_index }}][elements][{{ $element_index ?? 'create' }}][table]"
                style="width:100%;"
            >
                <option value=""></option>
                @foreach($tables as $table)
                    @if(isset($element->table) && $element->table == $table->id)
                        <option value="{{ $table->id }}" selected>{{ $table->label ?? '' }}</option>
                    @else
                        <option value="{{ $table->id }}">{{ $table->label ?? '' }}</option>
                    @endif
                @endforeach
            </select>
        </div>
    </div>
</div>

<h6><span class="bold">Svarsalternativ</span><span class="text-link float-right edit-translation text-normal" data-target="checkbox" data-mode="show">Redigera språk</span></h6>

<!-- Current options wrapper -->
@if(isset($element->options) && !empty($element->options))
    <!--loopa befintliga options-->
@endif

<!-- New options wrapper -->
<span class="append-options-to checkbox-wrapper mt-3"></span>

<!-- Add button for options -->
<div class="row mt-3">
    <div class="col-12 col-md-6">
        <span class="doAddOption btn btn-block btn-outline-primary">
            <i class="essential-xs essential-add"></i> Lägg till svarsalternativ
        </span>
    </div>
</div>

<!-- Required checkbox -->
<div class="row mt-3">
    <div class="col-12">
        <div class="custom-control custom-checkbox d-flex align-items-center mb-2">
            <input name="sections[{{ $section_index }}][elements][{{ $element_index ?? 'create' }}][required]"
                   type="checkbox"
                   class="custom-control-input"
                   id="section_{{ $section_index }}_element_{{ $element_index ?? 'create' }}_required"
                   @if(isset($element) && $element->pivot->required == 1) checked @endif
                   value="1" >
            <label class="custom-control-label pointer" for="section_{{ $section_index }}_element_{{ $element_index ?? 'create' }}_required">
                Frågan är ett krav och måste besvaras
            </label>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function(){

        $('.select-table').select2({
            placeholder: "Välj tabell",
            allowClear: true,
            minimumResultsForSearch: -1
        });

        $('.edit-translation').on('click', function(){
            let target  = $(this).attr('data-target');
            let mode    = $(this).attr('data-mode');

            $(`.${ target }-wrapper`).find('.translation').each(function(){
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

        $('.doAddOption').on('click', function(){
            let $template   = $('#option_template').clone();
            let $container  = $('.append-options-to');
            let count       = $container.children('div').length;

            $template.attr('id', `option_${ count }`);
            $template.removeClass('hidden');
            $template.find('.doRemoveOption').attr('data-option-id', `option_${ count }`);
            $template.children().each(function(){
                let iso = $(this).find('.checkbox-iso').val();

                $(this).find('.checkbox-input').attr('name', `sections[{{ $section_index }}][elements][{{ $element_index ?? 'create' }}][options][${ count }][labels][${ iso }]`);
                $(this).find('.checkbox-input').attr('id', `section_{{ $section_index }}_element_{{ $element_index ?? 'create' }}_option_${ count }_${ iso }`);
                $(this).find('label').attr('for', `section_{{ $section_index }}_element_{{ $element_index ?? 'create' }}_option_${ count }_${ iso }`);
                $(this).find('.option-label').text(count + 1);
            });

            $container.append($template);

            $("#elementEditModal_section_{{ $section_index }}_element_create_body").scrollTop($('#elementEditModal_section_{{ $section_index }}_element_create_body')[0].scrollHeight);
        });

        $(document).on('click', '.doRemoveOption', function(){
            let option_id = $(this).attr('data-option-id');
            $(`#${ option_id }`).remove();

            let count       = 0;
            let $container  = $('.append-options-to');
            $container.children('div').each(function(){
                $(this).find('.option-label').text(count + 1);

                $(this).children('div').each(function(){
                    let iso = $(this).find('.checkbox-iso').val();
                    $(this).find('.checkbox-input').attr('name', `sections[{{ $section_index }}][elements][{{ $element_index ?? 'create' }}][options][${ count }][labels][${ iso }]`);
                    $(this).find('.checkbox-input').attr('id', `section_{{ $section_index }}_element_{{ $element_index ?? 'create' }}_option_${ count }_${ iso }`);
                    $(this).find('label').attr('for', `section_{{ $section_index }}_element_{{ $element_index ?? 'create' }}_option_${ count }_${ iso }`);
                })

                count++;
            });
        });

    });
</script>

<style>
    .select2-selection__clear {
        margin-top: 0px;
    }
</style>

<!-- Templates -->
<div id="option_template" class="hidden">
    <small>Svarsalternativ #<span class="option-label">99</span></small>
@foreach($languages as $key => $lang)
    <!-- Checkbox -->
        <div class="row">
            <div class="col-12 @if($key !== $default_language) translation @endif" @if($key !== $default_language) style="display: none" @endif>
                <div class="mb-3 form-label-group form-group">
                    <input class="checkbox-iso" type="hidden" value="{{ $key }}">
                    <input
                            type="text"
                            name="sections[{{ $section_index }}][elements][{{ $element_index ?? 'create' }}][options][template][labels][{{ $key }}]"
                            id="section_{{ $section_index }}_element_{{ $element_index ?? 'create' }}_option_template_{{ $key }}"
                            class="form-control checkbox-input"
                            value=""
                    >
                    <label for="section_{{ $section_index }}_element_{{ $element_index ?? '' }}_option_template_label_{{ $key }}">
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
                        >
                            <i class="fal fa-times text-danger"></i>
                        </span>
                    @endif
                </div>
            </div>
        </div>
    @endforeach
</div>



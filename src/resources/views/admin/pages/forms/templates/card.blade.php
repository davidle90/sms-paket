<h6 class="bold element-label">
    {{ $element_label ?? '' }}
    @if($element_required === 'true' && !empty($element_label)) <i class="fa fa-asterisk required-marker" aria-hidden="true"></i> @endif
</h6>
<span class="element-description">{{ $element_description ?? '' }}</span>
<p><i class="text-danger element-required-text">{{ (isset($element_required_text)) ? '*'.$element_required_text : '' }}</i></p>

<!-- Input -->
@if($type_id == 1)
    <div class="row">
        <div class="col-12 col-sm-6 col-md-4 col-lg-3">
            <div class="form-label-group form-group mb-1">
                <input
                        type="text"
                        id="section_{{ $section_index }}_element_{{ $element_index }}_options_display"
                        class="form-control update-option"
                >
                <label for="section_{{ $section_index }}_element_{{ $element_index }}_options_display">
                    @ucfirst(language($default_language)->getNativeName()) ({{ language($default_language)->getName() }})
                </label>
            </div>
        </div>
    </div>
@endif

<!-- Textarea -->
@if($type_id == 6)
    <div class="row">
        <div class="col-12 col-sm-6 col-md-4 col-lg-4">
            <small>
                @ucfirst(language($default_language)->getNativeName()) ({{ language($default_language)->getName() }})
            </small>
            <div class="form-group mb-1">
                <textarea
                        id="section_{{ $section_index }}_element_{{ $element_index }}_options_display"
                        class="redactor-card form-control u-form__input update-option"
                ></textarea>
            </div>
        </div>
    </div>
@endif

<!-- Imported Table -->
@if(isset($table->label))
    <p class="mt-3 mb-1"><span class="bold">Importerad tabell:</span> {{ $table->label ?? '' }}</p>
@endif

<!-- Options -->
@if((isset($options) && !empty($options)) || isset($table->data))
    <p class="bold mt-3 mb-1">Svarsalternativ</p>

    <!-- Dropdown, Dropdown, multiselect-->
    @if($type_id == 2 || $type_id == 3)
        <div class="row">
            <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                <select
                        id="section_{{ $section_index }}_element_{{ $element_index }}_options_display"
                        class="form-control update-option"
                >
                    <option></option>
                    <!-- Table data -->
                    @if(isset($table->data))
                        @foreach($table->data as $data)
                            <option>{{ $data->in($default_language)->text ?? '' }}</option>
                        @endforeach
                    @endif
                    <!-- Option data -->
                    @foreach($options as $option)
                        <option>{{ $option ?? '' }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    @endif

    <!-- Checkbox -->
    @if($type_id == 4)
        <!-- Table data -->
        @if(isset($table->data))
            @foreach($table->data as $data_index => $data)
                <div class="custom-control custom-checkbox d-flex align-items-center mb-2">
                    <input
                            type="checkbox"
                            class="custom-control-input update-data"
                            id="section_{{ $section_index }}_element_{{ $element_index }}_table_data_display_{{ $data_index }}"
                            disabled
                    >
                    <label class="custom-control-label" for="section_{{ $section_index }}_element_{{ $element_index }}_table_data_display_{{ $data_index }}">
                        {{ $data->in($default_language)->text ?? '' }}
                    </label>
                </div>
            @endforeach
        @endif
        <!-- Option data -->
        @foreach($options as $option_index => $option)
            <div class="custom-control custom-checkbox d-flex align-items-center mb-2">
                <input
                        type="checkbox"
                        class="custom-control-input update-option"
                        id="section_{{ $section_index }}_element_{{ $element_index }}_options_display_{{ $option_index }}"
                        disabled
                >
                <label class="custom-control-label" for="section_{{ $section_index }}_element_{{ $element_index }}_options_display_{{ $option_index }}">
                    {{ $option ?? '' }}
                </label>
            </div>
        @endforeach
    @endif

    <!-- Radio -->
    @if($type_id == 5)
        <!-- Table data -->
        @if(isset($table->data))
            @foreach($table->data as $data_index => $data)
                <div class="form-check form-check-inline d-flex align-items-center mb-2">
                    <input
                            type="radio"
                            class="form-check-input update-data"
                            id="section_{{ $section_index }}_element_{{ $element_index }}_table_data_display_{{ $data_index }}"
                            disabled
                    >
                    <label class="form-check-label" for="section_{{ $section_index }}_element_{{ $element_index }}_table_data_display_{{ $data_index }}">
                        {{ $data->in($default_language)->text ?? '' }}
                    </label>
                </div>
            @endforeach
        @endif
        <!-- Option data -->
        @foreach($options as $option_index => $option)
            <div class="form-check form-check-inline d-flex align-items-center mb-2">
                <input
                        type="radio"
                        class="form-check-input update-option"
                        id="section_{{ $section_index }}_element_{{ $element_index }}_options_display_{{ $option_index }}"
                        disabled
                >
                <label class="form-check-label" for="section_{{ $section_index }}_element_{{ $element_index }}_options_display_{{ $option_index }}">
                    {{ $option ?? '' }}
                </label>
            </div>
        @endforeach
    @endif

@endif
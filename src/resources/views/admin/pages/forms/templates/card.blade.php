<div class="mb-2">
    <h6 class="mb-0 element-label">
        {{ $element_label ?? '' }}
        @if($element_required === 'true' && !empty($element_label)) <i class="fa fa-asterisk required-marker" aria-hidden="true"></i> @endif
    </h6>
    @if(isset($element_description))
        <p class="element-description mb-0">{{ $element_description ?? '' }}</p>
    @endif
</div>

<!-- Input -->
@if($type_id == 1)
    <div class="row">
        <div class="col-12 col-sm-6 col-md-4 col-lg-3">
            <div class="form-group mb-1">
                <input
                        type="text"
                        id="section_{{ $section_index }}_element_{{ $element_index }}_options_display"
                        class="form-control update-option"
                >
                <span><i class="text-danger element-required-text">{{ (isset($element_required_text)) ? '*'.$element_required_text : '' }}</i></span>
            </div>
        </div>
    </div>
@endif

<!-- Textarea -->
@if($type_id == 6)
    <div class="row">
        <div class="col-12 col-sm-6 col-md-4 col-lg-4">
            <div class="form-group mb-1">
                <textarea
                        id="section_{{ $section_index }}_element_{{ $element_index }}_options_display"
                        class="redactor-card form-control u-form__input update-option"
                ></textarea>
                <span><i class="text-danger element-required-text">{{ (isset($element_required_text)) ? '*'.$element_required_text : '' }}</i></span>
            </div>
        </div>
    </div>
@endif

<!-- Options -->
@if((isset($options) && !empty($options)) || isset($table->data))

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
                <span><i class="text-danger element-required-text">{{ (isset($element_required_text)) ? '*'.$element_required_text : '' }}</i></span>
            </div>
        </div>
    @endif

    <!-- Checkbox -->
    @if($type_id == 4)
        <div class="row">
            <!-- Table data -->
            @if(isset($table->data))
                @foreach($table->data as $data_index => $data)
                    <div class="{{ (isset($element_alignment) && $element_alignment === 'horizontal') ? 'col-12 col-sm-6 col-md-4 col-lg-3' : 'col-12' }}">
                        <div class="custom-control custom-checkbox d-flex align-items-center">
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
                    </div>
                @endforeach
            @endif
            <!-- Option data -->
            @foreach($options as $option_index => $option)
                <div class="{{ (isset($element_alignment) && $element_alignment === 'horizontal') ? 'col-12 col-sm-6 col-md-4 col-lg-3' : 'col-12' }}">
                    <div class="custom-control custom-checkbox d-flex align-items-center">
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
                </div>
            @endforeach
        </div>
        <div class="mt-1"><i class="text-danger element-required-text">{{ (isset($element_required_text)) ? '*'.$element_required_text : '' }}</i></div>
    @endif

    <!-- Radio -->
    @if($type_id == 5)
        <div class="row">
            <!-- Table data -->
            @if(isset($table->data))
                @foreach($table->data as $data_index => $data)
                    <div class="{{ (isset($element_alignment) && $element_alignment === 'horizontal') ? 'col-12 col-sm-6 col-md-4 col-lg-3' : 'col-12' }}">
                        <div class="custom-control custom-radio">
                            <input
                                    type="radio"
                                    class="custom-control-input"
                                    id="section_{{ $section_index }}_element_{{ $element_index }}_table_data_display_{{ $data_index }}"
                                    disabled
                            >
                            <label class="custom-control-label" for="section_{{ $section_index }}_element_{{ $element_index }}_table_data_display_{{ $data_index }}">
                                {{ $data->in($default_language)->text ?? '' }}
                            </label>
                        </div>
                    </div>
                @endforeach
            @endif
            <!-- Option data -->
            @foreach($options as $option_index => $option)
                <div class="{{ (isset($element_alignment) && $element_alignment === 'horizontal') ? 'col-12 col-sm-6 col-md-4 col-lg-3' : 'col-12' }}">
                    <div class="custom-control custom-radio">
                        <input
                                type="radio"
                                class="custom-control-input"
                                id="section_{{ $section_index }}_element_{{ $element_index }}_options_display_{{ $option_index }}"
                                disabled
                        >
                        <label class="custom-control-label" for="section_{{ $section_index }}_element_{{ $element_index }}_options_display_{{ $option_index }}">
                            {{ $option ?? '' }}
                        </label>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="mt-1"><i class="text-danger element-required-text">{{ (isset($element_required_text)) ? '*'.$element_required_text : '' }}</i></div>
    @endif

@endif

<!-- Imported Table -->
@if(isset($table->label))
    <p class="mt-3 mb-0"><span class="bold">Importerad tabell:</span> {{ $table->label ?? '' }}</p>
@endif
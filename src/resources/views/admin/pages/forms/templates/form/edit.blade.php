@if(isset($form) && !empty($form))
    <input type="hidden" name="form_id" value="{{ $form->id }}">
    <input type="hidden" name="form_iso" value="{{ $default_language }}">

    @foreach($form->sections as $section_index => $section)
        <h5 class="mt-3">{{ $section->in($default_language ?? $fallback_language)->label ?? '' }}</h5>
        <p>{{ $section->in($default_language ?? $fallback_language)->description ?? '' }}</p>

        <div class="row">
            @foreach($section->elements as $element_index => $element)
                @switch($element->type_id)
                    @case(1)
                        <!-- Input -->
                        <div class="{{ $element->pivot->size_class }}">
                            <div class="mb-2">
                                <h6 class="mb-0">
                                    {{ $element->in($default_language ?? $fallback_language)->label ?? '' }}
                                    @if($element->pivot->required == 1 && isset($element->in($default_language ?? $fallback_language)->label)) <i class="fa fa-asterisk required-marker" aria-hidden="true"></i> @endif
                                </h6>
                                @if(isset($element->in($default_language ?? $fallback_language)->description))
                                    <p class="mb-0">{{ $element->in($default_language ?? $fallback_language)->description }}</p>
                                @endif
                            </div>

                            <input type="hidden" name="bio[{{ $section_index }}][{{ $element_index }}][id]" value="{{ $element->id }}">
                            <input type="hidden" name="bio[{{ $section_index }}][{{ $element_index }}][type_id]" value="{{ $element->type_id }}">
                            <input type="hidden" name="bio[{{ $section_index }}][{{ $element_index }}][slug]" value="{{ $element->slug }}">

                            <div class="form-group">
                                <input
                                        type="text"
                                        name="bio[{{ $section_index }}][{{ $element_index }}][value]"
                                        id="section_{{ $section_index }}_element_{{ $element_index }}"
                                        class="form-control"
                                        value="{{ $element->data->where('response_id', $profile->current->form_response->id)->first()->sourceable->value ?? '' }}"
                                >
                                <span><i class="text-danger element-required-text">{{ (isset($element->in($default_language ?? $fallback_language)->required)) ? '*'.$element->in($default_language ?? $fallback_language)->required : '' }}</i></span>
                            </div>
                        </div>
                        @break
                    @case(2)
                        <!-- Dropdown -->
                        <div class="{{ $element->pivot->size_class }}">
                            <div class="mb-2">
                                <h6 class="mb-0">
                                    {{ $element->in($default_language ?? $fallback_language)->label ?? '' }}
                                    @if($element->pivot->required == 1 && isset($element->in($default_language ?? $fallback_language)->label)) <i class="fa fa-asterisk required-marker" aria-hidden="true"></i> @endif
                                </h6>
                                @if(isset($element->in($default_language ?? $fallback_language)->description))
                                    <p class="mb-0">{{ $element->in($default_language ?? $fallback_language)->description }}</p>
                                @endif
                            </div>

                            <input type="hidden" name="bio[{{ $section_index }}][{{ $element_index }}][id]" value="{{ $element->id }}">
                            <input type="hidden" name="bio[{{ $section_index }}][{{ $element_index }}][type_id]" value="{{ $element->type_id }}">
                            <input type="hidden" name="bio[{{ $section_index }}][{{ $element_index }}][slug]" value="{{ $element->slug }}">

                            <div class="pmd-textfield form-group">
                                <select name="bio[{{ $section_index }}][{{ $element_index }}][value]" id="section_{{ $section_index }}_element_{{ $element_index }}" class="select-single pmd-select2 form-control" style="width:100%;">
                                    <option value=""></option>
                                    <!-- Table data -->
                                    @if(isset($element->table->data))
                                        @foreach($element->table->data as $data)
                                            <option
                                                    value="{{ $data->in($default_language ?? $fallback_language)->text ?? '' }}"
                                                    @if(isset($element->data->where('response_id', $profile->current->form_response->id)->first()->sourceable->value) && $element->data->where('response_id', $profile->current->form_response->id)->first()->sourceable->value === $data->in($default_language ?? $fallback_language)->text)
                                                        selected
                                                    @endif
                                            >{{ $data->in($default_language ?? $fallback_language)->text ?? '' }}</option>
                                        @endforeach
                                    @endif
                                    <!-- Option data -->
                                    @if(isset($element->options))
                                        @foreach($element->options as $option)
                                            <option
                                                    value="{{ $option->in($default_language ?? $fallback_language)->label ?? '' }}"
                                                    @if(isset($element->data->where('response_id', $profile->current->form_response->id)->first()->sourceable->value) && $element->data->where('response_id', $profile->current->form_response->id)->first()->sourceable->value === $option->in($default_language ?? $fallback_language)->label)
                                                        selected
                                                    @endif
                                            >{{ $option->in($default_language ?? $fallback_language)->label ?? '' }}</option>
                                        @endforeach
                                    @endif
                                </select>
                                <span><i class="text-danger element-required-text">{{ (isset($element->in($default_language ?? $fallback_language)->required)) ? '*'.$element->in($default_language ?? $fallback_language)->required : '' }}</i></span>
                            </div>
                        </div>
                        @break
                    @case(3)
                        <!-- Dropdown, multiselect -->
                        <div class="{{ $element->pivot->size_class }}">
                            <div class="mb-2">
                                <h6 class="mb-0">
                                    {{ $element->in($default_language ?? $fallback_language)->label ?? '' }}
                                    @if($element->pivot->required == 1 && isset($element->in($default_language ?? $fallback_language)->label)) <i class="fa fa-asterisk required-marker" aria-hidden="true"></i> @endif
                                </h6>
                                @if(isset($element->in($default_language ?? $fallback_language)->description))
                                    <p class="mb-0">{{ $element->in($default_language ?? $fallback_language)->description }}</p>
                                @endif
                            </div>

                            <input type="hidden" name="bio[{{ $section_index }}][{{ $element_index }}][id]" value="{{ $element->id }}">
                            <input type="hidden" name="bio[{{ $section_index }}][{{ $element_index }}][type_id]" value="{{ $element->type_id }}">
                            <input type="hidden" name="bio[{{ $section_index }}][{{ $element_index }}][slug]" value="{{ $element->slug }}">

                            @php
                                $multiple_data_array = [];

                                foreach ($element->data->where('response_id', $profile->current->form_response->id) as $response_data) {
                                    $multiple_data_array[] = $response_data->sourceable->value;
                                }
                            @endphp

                            <div class="form-group">
                                <select name="bio[{{ $section_index }}][{{ $element_index }}][value][]" id="section_{{ $section_index }}_element_{{ $element_index }}" class="select-multiple form-control" multiple>
                                    <!-- Table data -->
                                    @if(isset($element->table->data))
                                        @foreach($element->table->data as $data)
                                            <option
                                                    value="{{ $data->in($default_language ?? $fallback_language)->text ?? '' }}"
                                                    @if(in_array($data->in($default_language ?? $fallback_language)->text, $multiple_data_array))
                                                        selected
                                                    @endif
                                            >{{ $data->in($default_language ?? $fallback_language)->text ?? '' }}</option>
                                        @endforeach
                                    @endif
                                    <!-- Option data -->
                                    @if(isset($element->options))
                                        @foreach($element->options as $option)
                                            <option
                                                    value="{{ $option->in($default_language ?? $fallback_language)->label ?? '' }}"
                                                    @if(in_array($option->in($default_language ?? $fallback_language)->label, $multiple_data_array))
                                                        selected
                                                    @endif
                                            >{{ $option->in($default_language ?? $fallback_language)->label ?? '' }}</option>
                                        @endforeach
                                    @endif
                                </select>
                                <span><i class="text-danger element-required-text">{{ (isset($element->in($default_language ?? $fallback_language)->required)) ? '*'.$element->in($default_language ?? $fallback_language)->required : '' }}</i></span>
                            </div>
                        </div>
                        @break
                    @case(4)
                        <!-- Checkbox -->
                        <div class="{{ $element->pivot->size_class }}">
                            <div class="mb-2">
                                <h6 class="mb-0">
                                    {{ $element->in($default_language ?? $fallback_language)->label ?? '' }}
                                    @if($element->pivot->required == 1 && isset($element->in($default_language ?? $fallback_language)->label)) <i class="fa fa-asterisk required-marker" aria-hidden="true"></i> @endif
                                </h6>
                                @if(isset($element->in($default_language ?? $fallback_language)->description))
                                    <p class="mb-0">{{ $element->in($default_language ?? $fallback_language)->description }}</p>
                                @endif
                            </div>

                            <input type="hidden" name="bio[{{ $section_index }}][{{ $element_index }}][id]" value="{{ $element->id }}">
                            <input type="hidden" name="bio[{{ $section_index }}][{{ $element_index }}][type_id]" value="{{ $element->type_id }}">
                            <input type="hidden" name="bio[{{ $section_index }}][{{ $element_index }}][slug]" value="{{ $element->slug }}">

                            @php
                                $multiple_data_array = [];

                                foreach ($element->data->where('response_id', $profile->current->form_response->id) as $response_data) {
                                    $multiple_data_array[] = $response_data->sourceable->value;
                                }
                            @endphp

                            <div class="form-group">
                                <div class="row">
                                    <!-- Table data -->
                                    @if(isset($element->table->data))
                                        @foreach($element->table->data as $data_index => $data)
                                            <div class="{{ (isset($element->alignment) && $element->alignment === 'horizontal') ? 'col-12 col-sm-6 col-md-4 col-lg-3' : 'col-12' }}">
                                                <div class="custom-control custom-checkbox">

                                                    <input
                                                            type="checkbox"
                                                            class="custom-control-input pointer"
                                                            id="section_{{ $section_index }}_element_{{ $element_index }}_data_{{ $data_index }}"
                                                            value="{{ $data->in($default_language ?? $fallback_language)->text ?? '' }}"
                                                            name="bio[{{ $section_index }}][{{ $element_index }}][value][]"
                                                            @if(in_array($data->in($default_language ?? $fallback_language)->text, $multiple_data_array))
                                                            selected
                                                            @endif
                                                    >
                                                    <label class="custom-control-label pointer" for="section_{{ $section_index }}_element_{{ $element_index }}_data_{{ $data_index }}" style="margin-top: 3px;">
                                                        {{ $data->in($default_language ?? $fallback_language)->text ?? '' }}
                                                    </label>
                                                </div>
                                            </div>
                                        @endforeach
                                    @endif
                                    <!-- Option data -->
                                    @if(isset($element->options))
                                        @foreach($element->options as $option_index => $option)
                                            <div class="{{ (isset($element->alignment) && $element->alignment === 'horizontal') ? 'col-12 col-sm-6 col-md-4 col-lg-3' : 'col-12' }}">
                                                <div class="custom-control custom-checkbox">

                                                    <input
                                                            type="checkbox"
                                                            class="custom-control-input pointer"
                                                            id="section_{{ $section_index }}_element_{{ $element_index }}_option_{{ $option_index }}"
                                                            value="{{ $option->in($default_language ?? $fallback_language)->label ?? '' }}"
                                                            name="bio[{{ $section_index }}][{{ $element_index }}][value][]"
                                                            @if(in_array($option->in($default_language ?? $fallback_language)->label, $multiple_data_array))
                                                                checked
                                                            @endif
                                                    >
                                                    <label class="custom-control-label pointer" for="section_{{ $section_index }}_element_{{ $element_index }}_option_{{ $option_index }}" style="margin-top: 3px;">
                                                        {{ $option->in($default_language ?? $fallback_language)->label ?? '' }}
                                                    </label>
                                                </div>
                                            </div>
                                        @endforeach
                                    @endif
                                </div>
                                <div class="mt-1"><i class="text-danger element-required-text">{{ (isset($element->in($default_language ?? $fallback_language)->required)) ? '*'.$element->in($default_language ?? $fallback_language)->required : '' }}</i></div>
                            </div>
                        </div>
                        @break
                    @case(5)
                        <!-- Radio -->
                        <div class="{{ $element->pivot->size_class }}">
                            <div class="mb-2">
                                <h6 class="mb-0">
                                    {{ $element->in($default_language ?? $fallback_language)->label ?? '' }}
                                    @if($element->pivot->required == 1 && isset($element->in($default_language ?? $fallback_language)->label)) <i class="fa fa-asterisk required-marker" aria-hidden="true"></i> @endif
                                </h6>
                                @if(isset($element->in($default_language ?? $fallback_language)->description))
                                    <p class="mb-0">{{ $element->in($default_language ?? $fallback_language)->description }}</p>
                                @endif
                            </div>

                            <input type="hidden" name="bio[{{ $section_index }}][{{ $element_index }}][id]" value="{{ $element->id }}">
                            <input type="hidden" name="bio[{{ $section_index }}][{{ $element_index }}][type_id]" value="{{ $element->type_id }}">
                            <input type="hidden" name="bio[{{ $section_index }}][{{ $element_index }}][slug]" value="{{ $element->slug }}">

                            <div class="form-group">
                                <div class="row">
                                    <!-- Table data -->
                                    @if(isset($element->table->data))
                                        @foreach($element->table->data as $data_index => $data)
                                            <div class="{{ (isset($element->alignment) && $element->alignment === 'horizontal') ? 'col-12 col-sm-6 col-md-4 col-lg-3' : 'col-12' }}">
                                                <div class="custom-control custom-radio">

                                                    <input
                                                            type="radio"
                                                            class="custom-control-input pointer"
                                                            id="section_{{ $section_index }}_element_{{ $element_index }}_data_{{ $data_index }}"
                                                            value="{{ $data->in($default_language ?? $fallback_language)->text ?? '' }}"
                                                            name="bio[{{ $section_index }}][{{ $element_index }}][value]"
                                                            @if(isset($element->data->where('response_id', $profile->current->form_response->id)->first()->sourceable->value) && $element->data->where('response_id', $profile->current->form_response->id)->first()->sourceable->value === $data->in($default_language ?? $fallback_language)->text)
                                                            checked
                                                            @endif
                                                    >
                                                    <label class="custom-control-label pointer" for="section_{{ $section_index }}_element_{{ $element_index }}_data_{{ $data_index }}" style="margin-top: 3px;">
                                                        {{ $data->in($default_language ?? $fallback_language)->text ?? '' }}
                                                    </label>
                                                </div>
                                            </div>
                                        @endforeach
                                    @endif
                                    <!-- Option data -->
                                    @if(isset($element->options))
                                        @foreach($element->options as $option_index => $option)
                                            <div class="{{ (isset($element->alignment) && $element->alignment === 'horizontal') ? 'col-12 col-sm-6 col-md-4 col-lg-3' : 'col-12' }}">
                                                <div class="custom-control custom-radio">

                                                    <input
                                                            type="radio"
                                                            class="custom-control-input pointer"
                                                            id="section_{{ $section_index }}_element_{{ $element_index }}_option_{{ $option_index }}"
                                                            value="{{ $option->in($default_language ?? $fallback_language)->label ?? '' }}"
                                                            name="bio[{{ $section_index }}][{{ $element_index }}][value]"
                                                            @if(isset($element->data->where('response_id', $profile->current->form_response->id)->first()->sourceable->value) && $element->data->where('response_id', $profile->current->form_response->id)->first()->sourceable->value === $option->in($default_language ?? $fallback_language)->label)
                                                                checked
                                                            @endif
                                                    >
                                                    <label class="custom-control-label pointer" for="section_{{ $section_index }}_element_{{ $element_index }}_option_{{ $option_index }}" style="margin-top: 3px;">
                                                        {{ $option->in($default_language ?? $fallback_language)->label ?? '' }}
                                                    </label>
                                                </div>
                                            </div>
                                        @endforeach
                                    @endif
                                </div>
                                <div class="mt-1"><i class="text-danger element-required-text">{{ (isset($element->in($default_language ?? $fallback_language)->required)) ? '*'.$element->in($default_language ?? $fallback_language)->required : '' }}</i></div>
                            </div>
                        </div>
                        @break
                    @case(6)
                        <!-- Textarea -->
                        <div class="{{ $element->pivot->size_class }}">
                            <div class="mb-2">
                                <h6 class="mb-0">
                                    {{ $element->in($default_language ?? $fallback_language)->label ?? '' }}
                                    @if($element->pivot->required == 1 && isset($element->in($default_language ?? $fallback_language)->label)) <i class="fa fa-asterisk required-marker" aria-hidden="true"></i> @endif
                                </h6>
                                @if(isset($element->in($default_language ?? $fallback_language)->description))
                                    <p class="mb-0">{{ $element->in($default_language ?? $fallback_language)->description }}</p>
                                @endif
                            </div>

                            <input type="hidden" name="bio[{{ $section_index }}][{{ $element_index }}][id]" value="{{ $element->id }}">
                            <input type="hidden" name="bio[{{ $section_index }}][{{ $element_index }}][type_id]" value="{{ $element->type_id }}">
                            <input type="hidden" name="bio[{{ $section_index }}][{{ $element_index }}][slug]" value="{{ $element->slug }}">

                            <div class="form-group">
                                <textarea name="bio[{{ $section_index }}][{{ $element_index }}][value]" class="redactor-textarea form-control u-form__input">
                                    {{ $element->data->where('response_id', $profile->current->form_response->id)->first()->sourceable->value ?? '' }}
                                </textarea>
                                <span><i class="text-danger element-required-text">{{ (isset($element->in($default_language ?? $fallback_language)->required)) ? '*'.$element->in($default_language ?? $fallback_language)->required : '' }}</i></span>
                            </div>
                        </div>
                        @break
                @endswitch
            @endforeach
        </div>
    @endforeach
@else
    <div class="col-12 mt-4 mb-4">
        <h5 class="text-secondary text-center">Inget formulär kopplat.</h5>
    </div>
@endif

<style>
    .select2-selection__clear {
        margin-top: 0px;
    }
</style>

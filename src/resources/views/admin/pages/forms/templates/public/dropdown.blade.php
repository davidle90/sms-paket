<div class="form-group-wrapper">
    <div class="form-group">
        <label for="name" class="control-label">{{$element->label}}
            @if($element->attr_required == 1)
                <i class="fa fa-asterisk required-marker" aria-hidden="true"></i>
            @endif
        </label>
        @if(!empty($element->help_text))
            <span class="help-text">{{$element->help_text}}</span>
        @endif

        <select class="form-control" name="elements[{{$element->id}}][0]" @if($element->attr_required == 1) @if(!empty($element->required_text)) data-fv-notempty-message="{{$element->required_text}}" @else data-fv-notempty-message="This field is required!" @endif required @endif>
            @if(isset($element->options) && !$element->options->isEmpty())
                @foreach($element->options as $option)
                    <option data-other-option="@if($option->other == 1){{1}}@else{{0}}@endif" value="{{$option->id}}">{{$option->label}}</option>
                @endforeach
            @endif
        </select>

		{{--
        <div class="element-other-option-wrapper" style="margin-top:10px; display:none;">

            @if(isset($element->options) && !$element->options->isEmpty())

                @foreach($element->options as $option)

                    @if($option->other == 1)
                        <label class="control-label">
                            {{$option->label}} @if($element->attr_required == 1)<i class="text-red fa fa-asterisk"></i>@endif
                        </label>
                    @endif

                @endforeach

            @endif

            <input type="text" class="form-control element-option-other-input hidden" name="elements[{{$element->id}}]" value="" @if($element->attr_required == 1) @if(!empty($element->required_text)) data-fv-notempty-message="{{$element->required_text}}" @else data-fv-notempty-message="This field is required!" @endif required @endif disabled/>
        </div>
        --}}
	</div>
</div>
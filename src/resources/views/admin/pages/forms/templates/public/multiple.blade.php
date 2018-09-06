<div class="form-group-wrapper">
    <div class="form-group">
        <label class="">{{$element->label}}
            @if($element->attr_required == 1)
                <i class="fa fa-asterisk required-marker" aria-hidden="true"></i>
            @endif
        </label>
        @if(!empty($element->help_text))
            <span class="help-text">{{$element->help_text}}</span>
        @endif

        @if(isset($element->options) && !$element->options->isEmpty())

            <div class="@if($element->attr_multiple){{'checkbox-group'}}@else{{'radio-group'}}@endif @if($element->attr_required == 1) required @endif" @if($element->attr_required == 1 && !empty($element->required_text)) data-fv-notempty-message="{{$element->required_text}}" @else data-fv-notempty-message="This field is required!" @endif required>

                @foreach($element->options as $option)

                    @if($element->attr_multiple == 1)

                        <label>
                            <input type="checkbox" @if($option->other == 1) data-other-option="1" @else data-other-option="0" @endif value="@if($option->other == 1){{''}}@else{{ $option->id }}@endif" name="elements[{{$element->id}}][]" @if($element->attr_required == 1) required @endif /> {{$option->label}}

                            @if($option->other == 1)
                                <div class="element-other-option-wrapper hidden">
                                    <input type="text" class="form-control element-option-other-input" name="elements[{{$element->id}}][]" value="" disabled/>
                                </div>
                            @endif

                        </label><br/>
                    @elseif($element->attr_multiple == 0)

                        <label>
                            <input type="radio" @if($option->other == 1) class="element-option-other" @endif name="elements[{{$element->id}}][0]" value="{{$option->id}}" @if($element->attr_required == 1) required @endif/> {{$option->label}}
                            @if($option->other == 1)
                                <div class="element-other-option-wrapper hidden">
                                    <input type="text" class="form-control element-option-other-input" name="elements[{{$element->id}}]" value="" disabled/>
                                </div>
                            @endif
                        </label><br/>

                    @endif

                @endforeach

            </div>

        @endif
	</div>
</div>
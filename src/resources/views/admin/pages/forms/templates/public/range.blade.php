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

        <br/>

        <input class="rangeslider" type="text" name="elements[{{$element->id}}]" data-slider-min="@if(isset($element->options[0]->label)){{$element->options[0]->label}}@else{{1}}@endif" data-slider-max="@if(isset($element->options[1]->label)){{$element->options[1]->label}}@else{{10}}@endif" data-slider-step="@if(isset($element->options[2]->label)){{$element->options[2]->label}}@else{{1}}@endif" data-slider-value="@if(isset($element->options[0]->label)){{$element->options[0]->label}}@else{{1}}@endif" @if($element->attr_required == 1) @if(!empty($element->required_text)) data-fv-notempty-message="{{$element->required_text}}" @else data-fv-notempty-message="This field is required!" @endif required @endif/>

    </div>


</div>
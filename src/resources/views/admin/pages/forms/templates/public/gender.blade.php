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
            <div class="form-check radio-group @if($element->attr_required == 1) required @endif" @if($element->attr_required == 1 && !empty($element->required_text)) data-required-message="{{$element->required_text}}" @else data-required-message="This field is required!" @endif required>
                @foreach($element->options as $option)
                    <label class="form-check-label">
                        <input type="radio" class="form-check-input" name="elements[{{$element->id}}]" value="{{$option->label}}" @if($element->attr_required == 1) required @endif/> {{$option->label}}
                    </label><br/>
                @endforeach
            </div>
        @endif

        <p class="help-block"></p>
    </div>
</div>
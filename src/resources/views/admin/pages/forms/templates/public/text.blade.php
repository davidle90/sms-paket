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
        <input type="text" class="form-control" name="elements[{{$element->id}}]" placeholder="" @if($element->attr_required == 1) @if(!empty($element->required_text)) data-fv-notempty-message="{{$element->required_text}}" @else data-fv-notempty-message="This field is required!" @endif required @endif/>
    </div>
</div>
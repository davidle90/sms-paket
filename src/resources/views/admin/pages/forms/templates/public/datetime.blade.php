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

        <div class="input-group datetimepicker">
            <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
            <input type="text" class="form-control" value="" placeholder="yyyy-mm-dd">
        </div>

        <p class="help-block"></p>

    </div>
</div>
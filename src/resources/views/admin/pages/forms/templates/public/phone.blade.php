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

        <div class="row">
            <div class="col-xs-12 col-sm-4 col-md-3">
                <select class="form-control form-control-xs">
                    <option value="46" data-iso-code="SE" selected>Sweden</option>
                    <option value="45" data-iso-code="DK">Denmark</option>
                    <option value="358" data-iso-code="FI">Finland</option>
                    <option value="1" data-iso-code="US">United States</option>
                </select>
            </div>
            <div class="col-xs-12 col-sm-8 col-md-9">
                <div class="input-group">
                    <span class="input-group-addon">+46</span>
                    <input type="text" class="form-control" value="">
                </div>
            </div>
        </div>

        <p class="help-block"></p>

    </div>

</div>
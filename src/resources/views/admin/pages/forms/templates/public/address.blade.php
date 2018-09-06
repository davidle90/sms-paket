<div class="form-group-wrapper">
    <label for="name" class="control-label">{{$element->label}}
        @if($element->attr_required == 1)
            <i class="fa fa-asterisk required-marker" aria-hidden="true"></i>
        @endif
    </label>

    @if(!empty($element->help_text))
        <span class="help-text">{{$element->help_text}}</span>
    @endif

    <!-- Street 1-->
    <div class="form-group row">
        <label class="col-sm-2 control-label" for="textinput">Street 1</label>
        <div class="col-sm-10">
            <input type="text" placeholder="Street 1" class="form-control">
        </div>
    </div>
    <!-- Street 2-->
    <div class="form-group row">
        <label class="col-sm-2 control-label" for="textinput">Street 2</label>
        <div class="col-sm-10">
            <input type="text" placeholder="Street 2" class="form-control">
        </div>
    </div>
    <!-- City-->
    <div class="form-group row">
        <label class="col-sm-2 control-label" for="textinput">City</label>
        <div class="col-sm-10">
            <input type="text" placeholder="City" class="form-control">
        </div>
    </div>
    <!-- State-->
    <div class="form-group row">
        <label class="col-sm-2 control-label" for="textinput">State</label>
        <div class="col-sm-4">
            <input type="text" placeholder="State" class="form-control">
        </div>

        <label class="col-sm-2 control-label" for="textinput">Postcode</label>
        <div class="col-sm-4">
            <input type="text" placeholder="Post Code" class="form-control">
        </div>
    </div>
    <!-- Country-->
    <div class="form-group row">
        <label class="col-sm-2 control-label" for="textinput">Country</label>
        <div class="col-sm-10">
            <select name="deliveryAddress_country" type="text" value="" class="form-control" required="required">
                <option selected="selected" disabled="disabled" value="">Select Country</option>
                <option value="8">Sweden</option>
            </select>
        </div>
    </div>
</div>

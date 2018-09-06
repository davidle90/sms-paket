<div class="element" data-unique-id="{{$i}}">


	<h5>#Q{{ $i+1 }} - Matrix / Rating scale <span class="pull-right"><i class="text-muted-light essential essential-settings-5 element-settings pointer" aria-hidden="true"></i></span></h5>


	<input type="hidden" name="element[{{$i}}][id]" value="{{$element->id or ''}}" />
	<input type="hidden" name="element[{{$i}}][list_element_id]" value="{{$element->list_element_id or '9'}}" />
	<input type="hidden" name="element[{{$i}}][sort_order]" value="{{$element->sort_order or 0}}" />

	<div class="form-group">
		<label class="control-label">Enter your question</label>
		<input class="form-control" type="text" name="element[{{$i}}][label]" value="{{$element->label or ''}}" />
	</div>
	<div class="form-group">
		<label class="control-label">Write an instruction</label>
		<input class="form-control" type="text" name="element[{{$i}}][help_text]" value="{{$element->help_text or ''}}" />
	</div>

    <div class="pdn-md"></div>

	<span class="bold">Rows</span>
	<div class="matrix-rows">
        <label>#1</label>
		<div class="input-group">
			<input class="form-control" type="text" name="" value="" />
			<span class="input-group-addon pointer">
				<i class="flaticon flaticon-garbage element-answers-delete-option" aria-hidden="true"></i>
			</span>
		</div>

        <label>#2</label>
		<div class="input-group">
			<input class="form-control" type="text" name="" value="" />
			<span class="input-group-addon pointer">
				<i class="flaticon flaticon-garbage element-answers-delete-option" aria-hidden="true"></i>
			</span>
		</div>

        <label>#3</label>
		<div class="input-group">
			<input class="form-control" type="text" name="" value="" />
			<span class="input-group-addon pointer">
				<i class="flaticon flaticon-garbage element-answers-delete-option" aria-hidden="true"></i>
			</span>
		</div>
	</div>
	<span class="pull-right">
		<a href="#" class="btn btn-primary element-answers-add-option">Add row</a>
	</span>

	<div class="pdn-md"></div>

	<span class="bold">Columns</span>
	<div class="matrix-columns">
        <label>#1</label>
		<div class="input-group">
			<input class="form-control" type="text" name="" value="" />
			<span class="input-group-addon pointer">
				<i class="flaticon flaticon-garbage element-answers-delete-option" aria-hidden="true"></i>
			</span>
		</div>

        <label>#2</label>
		<div class="input-group">
			<input class="form-control" type="text" name="" value="" />
			<span class="input-group-addon pointer">
				<i class="flaticon flaticon-garbage element-answers-delete-option" aria-hidden="true"></i>
			</span>
		</div>

        <label>#3</label>
		<div class="input-group">
			<input class="form-control" type="text" name="" value="" />
			<span class="input-group-addon pointer">
				<i class="flaticon flaticon-garbage element-answers-delete-option" aria-hidden="true"></i>
			</span>
		</div>
	</div>
	<span class="pull-right">
		<a href="#" class="btn btn-primary element-answers-add-option">Add column</a>
	</span>

	<div class="clearfix"></div>
	<div style="margin-bottom:20px;"></div>

	<div class="element-options">

		<!-- Modal -->
		<div class="modal fade" id="ElementSettingsModal_{{$i}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
			<div class="modal-dialog" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" id="exampleModalLongTitle">
							Matrix / Rating scale settings
							<button type="button" class="close pull-right" data-dismiss="modal" aria-label="Close">
								<span aria-hidden="true">&times;</span>
							</button>
						</h5>
					</div>
					<div class="modal-body">

						<h5>Options</h5>

						<div class="form-group">
							<label class="checkbox primary">
								<input class="custom-checkbox" data-toggle="radio" value="0" required="" type="checkbox" name="element[{{$i}}][required]" @if(isset($element->attr_required) && $element->attr_required == 1) checked @endif />
								<span class="icons">
									<span class="icon-unchecked"></span>
									<span class="icon-checked"></span>
								</span>
								This question is voluntary and does not need to be answered
							</label>
						</div>

						<div class="form-group">
							<label class="checkbox primary">
								<input class="custom-checkbox element-option-other-toggle" data-toggle="radio" value="1" required="" type="checkbox" name="element[{{$i}}][other]" @if(isset($element->other) && $element->other == 1) checked @endif />
								<span class="icons">
									<span class="icon-unchecked"></span>
									<span class="icon-checked"></span>
								</span>
								Add an "other" answer option or comment field
							</label>
						</div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-danger float-left element-delete" data-dismiss="modal">Delete</button>
						<button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
					</div>
				</div>
			</div>
		</div>

	</div>

	<div class="templates" style="display: none;">

        <div class="answer template-answer">
            <label class="control-label">Answer choice #<span class=""></span></label>
            <div class="input-group">
                <input type="hidden" name="replace_id" value="" />
                <input type="hidden" name="replace_other" value="0" />
                <input type="hidden" name="replace_sort_order" value="0" />

                <input class="form-control col-md-8" type="text" name="replace_label" value="" />
                <span class="input-group-addon pointer">
                    <i class="flaticon flaticon-garbage element-answers-delete-option" aria-hidden="true"></i>
                </span>
            </div>
        </div>

	</div>

</div>
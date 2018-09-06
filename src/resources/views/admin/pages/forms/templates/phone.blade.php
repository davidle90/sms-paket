<div class="element" data-unique-id="{{$i}}">

	<input type="hidden" name="element[{{$i}}][id]" value="{{$element->id or ''}}" />
	<input type="hidden" name="element[{{$i}}][list_element_id]" value="{{$element->list_element_id or '6'}}" />

	<h5>#Q{{ $i+1 }} - Phone <span class="pull-right"><i class="text-muted-light essential essential-settings-5 element-settings pointer" aria-hidden="true"></i></span></h5>

	<div class="form-group">
		<label class="control-label">Enter your question</label>
		<input class="form-control" type="text" name="element[{{$i}}][label]" value="{{$element->label or 'What is your phone number?'}}" />
	</div>
	<div class="form-group">
		<label class="control-label">Write an instruction</label>
		<input class="form-control" type="text" name="element[{{$i}}][help_text]" value="{{$element->help_text or ''}}" />
	</div>

	<div class="clearfix"></div>
	<div style="margin-bottom:20px;"></div>

	<div class="element-options">

		<!-- Modal -->
		<div class="modal fade" id="ElementSettingsModal_{{$i}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
			<div class="modal-dialog" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" id="exampleModalLongTitle">
							Phone settings
							<button type="button" class="close pull-right" data-dismiss="modal" aria-label="Close">
								<span aria-hidden="true">&times;</span>
							</button>
						</h5>

					</div>
					<div class="modal-body">

						<h5>Options</h5>

						<div class="form-group">
							<label class="checkbox primary">
								<input class="custom-checkbox" data-toggle="radio" value="0" required="" type="checkbox" name="element[{{$i}}][required]" @if(isset($element) && $element->attr_required == 1) checked @endif />
								<span class="icons">
									<span class="icon-unchecked"></span>
									<span class="icon-checked"></span>
								</span>
								This question is voluntary and does not need to be answered
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

</div>
<div class="element" data-unique-id="{{$i}}">

	<h5>#Q{{ $i+1 }} - Gender <span class="pull-right"><i class="text-muted-light essential essential-settings-5 element-settings pointer" aria-hidden="true"></i></span></h5>

	<input type="hidden" name="element[{{$i}}][id]" value="{{$element->id ?? ''}}" />
	<input type="hidden" name="element[{{$i}}][list_element_id]" value="{{$element->list_element_id ?? '5'}}" />

	<div class="form-group">
		<label class="control-label">Enter your question</label>
		<input class="form-control" type="text" name="element[{{$i}}][label]" value="{{$element->label ?? 'What is your gender?'}}" />
	</div>
	<div class="form-group">
		<label class="control-label">Write an instruction</label>
		<input class="form-control" type="text" name="element[{{$i}}][help_text]" value="{{$element->help_text ?? ''}}" />
	</div>

    <div class="pdn-md"></div>

	<div class="element-answers" data-answers-count="@if(isset($element->options)){{$element->options->count()}}@else{{1}}@endif">


		<div class="answers">
			@if(isset($element->options) && !$element->options->isEmpty())
				@foreach($element->options as $key => $option)

					<div class="input-group answer">
						<input type="hidden" name="element[{{$i}}][options][{{$key}}][id]" value="{{$option->id ?? ''}}" />
						<input type="hidden" name="element[{{$i}}][options][{{$key}}][other]" value="{{$option->other or 0}}" />
						<input type="hidden" name="element[{{$i}}][options][{{$key}}][sort_order]" value="{{$option->sort_order or 0}}" />
						<input type="hidden" name="element[{{$i}}][options][{{$key}}][label]" value="{{ $option->label ?? '' }}" />
					</div>
				@endforeach
			@else
				@foreach(['Male','Female'] as $gkey => $gender)
					<div class="input-group answer">
						<input type="hidden" name="element[{{$i}}][options][{{$gkey}}][id]" value="" />
						<input type="hidden" name="element[{{$i}}][options][{{$gkey}}][other]" value="0" />
						<input type="hidden" name="element[{{$i}}][options][{{$gkey}}][sort_order]" value="0" />
						<input type="hidden" name="element[{{$i}}][options][{{$gkey}}][label]" value="{{$gender}}" />
					</div>
				@endforeach
			@endif
		</div>

	</div>
	<span class="pull-right">
		<a href="#" class="btn btn-primary element-answers-add-option">Add answer choice</a>
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
							Gender settings
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
            <label class="control-label">Answer choice #<span class="answer-number"></span></label>
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
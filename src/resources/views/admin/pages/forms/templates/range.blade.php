<div class="element" data-unique-id="{{$i}}">

	<input class="element-id" type="hidden" name="section[{{$y}}][element][{{$i}}][id]" value="{{$element->id ?? ''}}" />
	<input class="element-list-element-id" type="hidden" name="section[{{$y}}][element][{{$i}}][list_element_id]" value="{{$element->list_element_id ?? '10'}}" />
	<input class="element-sort-order" type="hidden" name="section[{{$y}}][element][{{$i}}][sort_order]" value="{{$element->sort_order or 0}}" />

	<h5 class="element-drag">#Q<span class="element-number">{{ $i+1 }}</span> - Range slider <span class="pull-right"><i class="text-muted-light essential essential-settings-5 element-settings pointer" aria-hidden="true"></i></span></h5>

	<div class="form-group">
		<label class="control-label">Enter your question</label>
		<input class="form-control element-label" type="text" name="section[{{$y}}][element][{{$i}}][label]" value="{{$element->label ?? ''}}" />
	</div>
	<div class="form-group">
		<label class="control-label">Write an instruction</label>
		<input class="form-control element-help-text" type="text" name="section[{{$y}}][element][{{$i}}][help_text]" value="{{$element->help_text ?? ''}}" />
	</div>

	<div class="element-answers" data-answers-count="@if(isset($element->options)){{$element->options->count()}}@else{{1}}@endif">

		<div class="answers">
			@if(isset($element->options) && !$element->options->isEmpty())
				@foreach($element->options as $key => $option)

					<div class="answer">
						<label class="control-label">
							@if($key == 0)
								Minimum
							@elseif($key == 1)
								Maximum
							@else
								Step
							@endif
						</label>
						<input class="element-option-id" type="hidden" name="section[{{$y}}][element][{{$i}}][options][{{$key}}][id]" value="{{$option->id ?? ''}}" />
						<input class="element-option-other" type="hidden" name="section[{{$y}}][element][{{$i}}][options][{{$key}}][other]" value="{{$option->other or 0}}" />
						<input class="element-option-sort-order" type="hidden" name="section[{{$y}}][element][{{$i}}][options][{{$key}}][sort_order]" value="{{$option->sort_order or 0}}" />
						<input class="form-control col-md-8 element-option-label" type="number" name="section[{{$y}}][element][{{$i}}][options][{{$key}}][label]" value="{{ $option->label ?? '' }}" />
					</div>
				@endforeach
			@else
				<div class="answer">
					<label class="control-label">Minimum</label>
					<input class="element-option-id" type="hidden" name="section[{{$y}}][element][{{$i}}][options][0][id]" value="" />
					<input class="element-option-other" type="hidden" name="section[{{$y}}][element][{{$i}}][options][0][other]" value="0" />
					<input class="element-option-sort-order" type="hidden" name="section[{{$y}}][element][{{$i}}][options][0][sort_order]" value="0" />
					<input class="form-control col-md-8 element-option-label" type="number" name="section[{{$y}}][element][{{$i}}][options][0][label]" value="" />
				</div>
				<div class="answer">
					<label class="control-label">Maximum</label>
					<input class="element-option-id" type="hidden" name="section[{{$y}}][element][{{$i}}][options][1][id]" value="" />
					<input class="element-option-other" type="hidden" name="section[{{$y}}][element][{{$i}}][options][1][other]" value="0" />
					<input class="element-option-sort-order" type="hidden" name="section[{{$y}}][element][{{$i}}][options][1][sort_order]" value="1" />
					<input class="form-control col-md-8 element-option-label" type="number" name="section[{{$y}}][element][{{$i}}][options][1][label]" value="" />
				</div>
				<div class="answer">
					<label class="control-label">Step</label>
					<input class="element-option-id" type="hidden" name="section[{{$y}}][element][{{$i}}][options][2][id]" value="" />
					<input class="element-option-other" type="hidden" name="section[{{$y}}][element][{{$i}}][options][2][other]" value="0" />
					<input class="element-option-sort-order" type="hidden" name="section[{{$y}}][element][{{$i}}][options][2][sort_order]" value="2" />
					<input class="form-control col-md-8 element-option-label" type="number" name="section[{{$y}}][element][{{$i}}][options][2][label]" value="" />
				</div>
			@endif
		</div>


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
							Range slider settings
							<button type="button" class="close pull-right" data-dismiss="modal" aria-label="Close">
								<span aria-hidden="true">&times;</span>
							</button>
						</h5>

					</div>
					<div class="modal-body">

						<h5>Options</h5>

						<div class="form-group">
							<label class="checkbox primary">
								<input class="custom-checkbox element-settings-options-required" data-toggle="radio" value="0" required="" type="checkbox" name="section[{{$y}}][element][{{$i}}][required]" @if(isset($element) && $element->attr_required == 0) checked @endif />
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
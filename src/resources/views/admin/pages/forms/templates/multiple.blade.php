<div class="element" data-unique-id="{{$i}}">

	<input class="element-id" type="hidden" name="section[{{$y}}][element][{{$i}}][id]" value="{{$element->id or ''}}" />
	<input class="element-list-element-id" type="hidden" name="section[{{$y}}][element][{{$i}}][list_element_id]" value="{{$element->list_element_id or '1'}}" />
	<input class="element-sort-order" type="hidden" name="section[{{$y}}][element][{{$i}}][sort_order]" value="{{$element->sort_order or 0}}" />

	<span class="element-drag">
		<b>Fråga <span class="element-number">{{ $i+1 }}</span> - Checkbox / Radio knappar</b>
		<span class="pull-right">
			<i class="text-muted-light essential essential-edit element-settings pointer" aria-hidden="true"></i>
		</span>
	</span>

	<div class="element-view">
		<div class="form-group">
			<label for="" class="control-label bold">
				<span id="element_{{ $i }}_title">
					@if(isset($element->label) && !empty($element->label))
						{{ $element->label }}
					@else
						<i class="text-danger">Titel på din fråga</i>
					@endif
				</span>
				<i id="element_{{ $i }}_required" class="fa fa-asterisk required-marker" aria-hidden="true" style="@if(isset($element) && $element->attr_required == 0) display:none @endif"></i>
			</label>
			<p id="element_{{ $i }}_helptext" class="help-block">
				@if(isset($element->help_text) && !empty($element->help_text))
					{{ $element->help_text }}
				@else
					<i class="text-danger">Hjälp text som förklarar vad man förväntas att välja</i>
				@endif
			</p>

				<?php $multiple_type = (isset($element->attr_multiple) && $element->attr_multiple == 1) ? 'checkbox' : 'radio'; ?>

			<div id="element_{{ $i }}_multiple">
				@if(isset($element->options) && !$element->options->isEmpty())
					@foreach($element->options as $key => $option)
						<div class="{{ $multiple_type }}">
							<label><input type="{{ $multiple_type }}" name="opt{{ $multiple_type }}" disabled> {!! $option->label or '<i>Svarsalternativ</i>' !!}</label>
						</div>
					@endforeach
				@else
					<div class="{{ $multiple_type }}">
						<label><input type="{{ $multiple_type }}" name="opt{{ $multiple_type }}" disabled> <i>Svarsalternativ #1</i></label>
					</div>
					<div class="{{ $multiple_type }}">
						<label><input type="{{ $multiple_type }}" name="opt{{ $multiple_type }}" disabled> <i>Svarsalternativ #2</i></label>
					</div>
					<div class="{{ $multiple_type }}">
						<label><input type="{{ $multiple_type }}" name="opt{{ $multiple_type }}" disabled> <i>Svarsalternativ #3</i></label>
					</div>
				@endif
			</div>
		</div>
	</div>

	<div class="element-options">

		<!-- Modal -->
		<div class="form-element-modal modal fade" data-element="multiple" data-id="{{ $i }}" id="ElementSettingsModal_{{$i}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
			<div class="modal-dialog" role="document">
				<div class="modal-content">
					<div class="modal-header danger">
						<h5 class="modal-title" id="exampleModalLongTitle">
							Inställningar för checkbox / radio knappar
						</h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true"><i class="text-white essential essential-xs essential-multiply"></i></span>
						</button>
					</div>
					<div class="modal-body">

						<p class="alert alert-info">
							Checkboxar eller radio knappar används när det redan finns färdiga svar som personen ska får välja mellan. Checkboxes används även när man ska få välja flera olika svar samtidigt.
						</p>

						<div class="pdn-xs"></div>

						<div class="form-group">
							<label class="control-label">Skriv en fråga (titel)</label>
							<input id="input_title_{{ $i }}" class="form-control element-label" type="text" name="section[{{$y}}][element][{{$i}}][label]" value="{{$element->label or ''}}" />
						</div>
						<div class="form-group">
							<label class="control-label">Skriv en instruktion (hjälp text)</label>
							<textarea id="input_helptext_{{ $i }}" class="form-control element-help-text" type="text" rows="5" name="section[{{$y}}][element][{{$i}}][help_text]">{{$element->help_text or ''}}</textarea>
						</div>

						<div class="pdn-xs"></div>

						@include('rl_forms::admin.pages.forms.templates.common.multipleanswers')

						<div class="form-group">
							<label class="checkbox primary">
								<input id="input_required_{{ $i }}" class="custom-checkbox element-settings-options-required" data-toggle="radio" value="0" required="" type="checkbox" name="section[{{$y}}][element][{{$i}}][required]" @if(isset($element->attr_required) && $element->attr_required == 0) checked @endif />
								<span class="icons">
									<span class="icon-unchecked"></span>
									<span class="icon-checked"></span>
								</span>
								Fråga är frivillig och behöver inte besvaras
							</label>
						</div>

						<div class="form-group">
							<label class="checkbox primary element-settings-options-multiple">
								<input id="input_checkbox_{{ $i }}" class="custom-checkbox" data-toggle="radio" value="1" required="" type="checkbox" name="section[{{$y}}][element][{{$i}}][multiple]" @if(isset($element->attr_multiple) && $element->attr_multiple == 1) checked @endif />
								<span class="icons">
									<span class="icon-unchecked"></span>
									<span class="icon-checked"></span>
								</span>
								Tillåt mer än ett svar för denna fråga (använd checkboxar)
							</label>
						</div>

						<div class="form-group" style="display: none;">
							<label class="checkbox primary">
								<input class="custom-checkbox element-settings-options-other element-option-other-toggle" data-toggle="radio" value="1" required="" type="checkbox" name="section[{{$y}}][element][{{$i}}][other]" @if(isset($element->other) && $element->other == 1) checked @endif />
								<span class="icons">
									<span class="icon-unchecked"></span>
									<span class="icon-checked"></span>
								</span>
								Add an "other" answer option or comment field
							</label>
						</div>
					</div>
					<div class="modal-footer">
						<span class="btn btn-link element-delete" data-dismiss="modal">Radera frågan</span>
						<span class="btn btn-secondary" data-dismiss="modal">Stäng</span>
					</div>
				</div>
			</div>
		</div>

	</div>

	<div class="templates" style="display: none;">

        <div class="answer template-answer">
            <label class="control-label">Svarsalternativ #<span class="element-option-number"></span></label>
            <div class="input-group">

                <input class="element-option-id" type="hidden" name="replace_id" value="" />
                <input class="element-option-other" type="hidden" name="replace_other" value="0" />
                <input class="element-option-sort-order" type="hidden" name="replace_sort_order" value="0" />

                <input class="form-control col-md-12 element-option-label" type="text" name="replace_label" value="" />
                <span class="input-group-addon pointer">
                    <i class="text-danger essential essential-xs essential-multiply element-answers-delete-option" aria-hidden="true"></i>
                </span>
            </div>
        </div>

	</div>

</div>
<div class="element" data-unique-id="{{$i}}">

	<input class="element-id" type="hidden" name="section[{{$y}}][element][{{$i}}][id]" value="{{$element->id ?? ''}}" />
	<input class="element-list-element-id" type="hidden" name="section[{{$y}}][element][{{$i}}][list_element_id]" value="{{$element->list_element_id ?? '3'}}" />
	<input class="element-sort-order" type="hidden" name="section[{{$y}}][element][{{$i}}][sort_order]" value="{{$element->sort_order or 0}}" />

	<span class="element-drag">
		<b>Fråga <span class="element-number">{{ $i+1 }}</span> - Text</b>
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
					<i class="text-danger">Hjälp text som förklarar vad man förväntas att skriva som svar</i>
				@endif
			</p>
			<input type="text" class="form-control" disabled />
		</div>
	</div>

	<div class="element-options">

		<!-- Modal -->
		<div class="form-element-modal modal fade" data-element="text" data-id="{{ $i }}" id="ElementSettingsModal_{{$i}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
			<div class="modal-dialog" role="document">
				<div class="modal-content">
					<div class="modal-header inverse">
						<h5 class="modal-title" id="exampleModalLongTitle">
							Inställningar för text
						</h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true"><i class="text-white essential essential-xs essential-multiply"></i></span>
						</button>
					</div>
					<div class="modal-body">

						<p class="alert alert-info">
							Text är en enskild rad där personen kan besvara en fråga. Använd fältet där det förväntade svaret består av ett eller ett par få ord.
						</p>

						<div class="pdn-xs"></div>

						<div class="form-group">
							<label class="control-label">Skriv en fråga (titel)</label>
							<input id="input_title_{{ $i }}" class="form-control element-label" type="text" name="section[{{$y}}][element][{{$i}}][label]" value="{{$element->label ?? ''}}" />
						</div>
						<div class="form-group">
							<label class="control-label">Skriv en instruktion (hjälp text)</label>
							<textarea id="input_helptext_{{ $i }}" class="form-control element-help-text" type="text" rows="5" name="section[{{$y}}][element][{{$i}}][help_text]">{{$element->help_text ?? ''}}</textarea>
						</div>

						<div class="pdn-xs"></div>

						<div class="form-group">
							<label class="checkbox primary">
								<input id="input_required_{{ $i }}" class="custom-checkbox element-settings-options-required" data-toggle="radio" value="0" required="" type="checkbox" name="section[{{$y}}][element][{{$i}}][required]" @if(isset($element) && $element->attr_required == 0) checked @endif />
								<span class="icons">
									<span class="icon-unchecked"></span>
									<span class="icon-checked"></span>
								</span>
								Fråga är frivillig och behöver inte besvaras
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

</div>
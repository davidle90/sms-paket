<div class="element-answers" data-answers-count="@if(isset($element->options)){{$element->options->count()}}@else{{1}}@endif">

    <div class="answers">
		@if(isset($element->options) && !$element->options->isEmpty())
			@foreach($element->options as $key => $option)

                <div class="answer">

                    <label class="grab control-label">Svarsalternativ #<span class="element-option-number">{{ $key+1 }}</span></label>

                    <div class="input-group">

                        <input class="element-option-id" type="hidden" name="section[{{$y}}][element][{{$i}}][options][{{$key}}][id]" value="{{$option->id ?? ''}}" />
                        <input class="element-option-other" type="hidden" name="section[{{$y}}][element][{{$i}}][options][{{$key}}][other]" value="{{$option->other or 0}}" />
                        <input class="element-option-sort-order" type="hidden" name="section[{{$y}}][element][{{$i}}][options][{{$key}}][sort_order]" value="{{$option->sort_order or 0}}" />

                        <input id="input_answers_{{ $key }}" class="form-control col-md-12 element-option-label" type="text" name="section[{{$y}}][element][{{$i}}][options][{{$key}}][label]" value="{{ $option->label ?? '' }}" />
                        <span class="input-group-addon pointer">
                            <i class="text-danger essential essential-xs essential-multiply element-answers-delete-option" aria-hidden="true"></i>
                        </span>
                    </div>
                </div>
			@endforeach
		@else
            {{--
            <div class="answer">
                <label class="control-label">Svarsalternativ #<span class="element-option-number">1</span></label>
                <div class="input-group">

                    <input class="element-option-id" type="hidden" name="section[{{$y}}][element][{{$i}}][options][0][id]" value="" />
                    <input class="element-option-other" type="hidden" name="section[{{$y}}][element][{{$i}}][options][0][other]" value="0" />
                    <input class="element-option-sort-order" type="hidden" name="section[{{$y}}][element][{{$i}}][options][0][sort_order]" value="0" />

                    <input class="form-control col-md-12 element-option-label" type="text" name="section[{{$y}}][element][{{$i}}][options][0][label]" value="" />
                    <span class="input-group-addon pointer">
                        <i class="text-danger essential essential-xs essential-multiply element-answers-delete-option" aria-hidden="true"></i>
                    </span>
                </div>
            </div>
            --}}
		@endif
	</div>

	<div class="other">

	</div>

</div>

<div class="pdn-xs"></div>
<span class="btn btn-success element-answers-add-option">Lägg till svarsalternativ</span>
<div class="pdn-sm"></div>

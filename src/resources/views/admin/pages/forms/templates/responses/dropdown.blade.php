<tr>
	<th style="width: 40%">
		{{$element->label}}
		@if($element->attr_required == 1)
			<i class="fa fa-asterisk required-marker"></i>
		@endif
	</th>
	<td>
		@if(isset($element->options) && !$element->options->isEmpty())
			@foreach($element->options as $option)
				@if(!$element->data->isEmpty() && !$element->data[0]->options->isEmpty() && $element->data[0]->options[0]->option_id == $option->id)
					{{$option->label}}
				@endif
			@endforeach
		@endif
	</td>
</tr>

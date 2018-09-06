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

				@if(isset($element->data[0]->options))
					@foreach($element->data[0]->options as $o)
						@if($o->option_id == $option->id)
							{{$option->label}}

							@if(!$loop->last)
								,
							@endif

							@break
						@endif
					@endforeach
				@endif


			@endforeach

		@endif
	</td>
</tr>

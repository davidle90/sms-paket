<tr>
	<th style="width: 40%">
		{{$element->label}}
		@if($element->attr_required == 1)
			<i class="fa fa-asterisk required-marker"></i>
		@endif
	</th>
	<td>@if(isset($element->data[0])){{$element->data[0]->value}}@endif</td>
</tr>
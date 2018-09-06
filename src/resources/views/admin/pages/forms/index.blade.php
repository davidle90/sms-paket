@extends('rl_webadmin::layouts.master')

@section('breadcrumbs')
	<!-- Breadcrumb -->
	<ol class="breadcrumb">
		<li class="breadcrumb-item active">Formulär</li>
		<!-- Breadcrumb Menu-->
		<li class="breadcrumb-menu d-md-down-none">
			<div class="btn-group" role="group" aria-label="Button group with nested dropdown">
				<a class="btn" href="{{ route('rl_forms.admin.forms.create') }}"><i class="icon-user"></i> Lägg till nytt formulär</a>
			</div>
		</li>
	</ol>
@endsection

@section('content')

	<!-- Update profile -->
	<div class="card">

		<!-- Card header -->
		<div class="card-header">
			<strong>Formlär</strong>
		</div>

		<!-- Card body -->
		<div class="card-body collapse show" id="collapseProfile">

			<table class="table table-hover">
				<thead>
					<tr>
						<th>Formulär</th>
						<th width="10px"></th>
					</tr>
				</thead>

				<tbody>
					@if(isset($forms) && !empty($forms))
						@foreach($forms as $f)
							<tr class="pointer go-to-url" data-url="{{ route('rl_forms.admin.forms.edit', ['id' => $f->id]) }}">
								<td>@if(!empty($f->label)){{ ucfirst($f->label) }}@else <i>Namnlöst</i> @endif</td>
								<td width="10px"></td>
							</tr>
						@endforeach
					@endif
				</tbody>
			</table>

		</div>
	</div>

@stop

@section('scripts')
	<script type="text/javascript">
		$(document).ready(function(){
			$('tr[data-url]').on('click', function(e){
				e.preventDefault();
				window.location = $(this).attr('data-url');
			});
		});
	</script>
@stop



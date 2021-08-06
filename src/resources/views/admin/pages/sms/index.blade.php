@extends('rl_webadmin::layouts.new_master')

@section('styles')
@endsection

@section('breadcrumbs')
	<!-- Breadcrumb -->
	<ol class="breadcrumb">
		<li class="breadcrumb-item active">Sms</li>
	</ol>
@endsection

@section('sidebar')
	{{--<a class="btn btn-block btn-outline-primary" href="{{ route('rl_sms.admin.sms.create') }}"><i class="essential-xs essential-add mr-1"></i>Skapa sms</a>--}}
@endsection

@section('content')
	<h1>This is SMS</h1>
@stop

@section('scripts')

	@if(Session::has('message'))
		<script type="text/javascript">
			$(document).ready(function(){

				message = '{{ Session::get('message') }}';

				toastr.success(message, 'Success!', toastr.options = {
					"closeButton": false,
					"debug": false,
					"newestOnTop": false,
					"progressBar": true,
					"positionClass": "toast-bottom-right",
					"preventDuplicates": false,
					"onclick": null,
					"showDuration": "300",
					"hideDuration": "1000",
					"timeOut": "5000",
					"extendedTimeOut": "1000",
					"showEasing": "swing",
					"hideEasing": "linear",
					"showMethod": "fadeIn",
					"hideMethod": "fadeOut"
				});
			});
		</script>
	@endif

	<script type="text/javascript">
		$(document).ready(function(){

			$('.go-to-url').on('click', function(e){
				if(!$(e.target).hasClass('dropdown-toggle') && !$(e.target).hasClass('do-delete-row')){
					goToURL = $(this).attr('data-url');
					window.location = goToURL;
				}
			});

		});
	</script>
@stop

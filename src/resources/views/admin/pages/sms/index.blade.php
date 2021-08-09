@extends('rl_webadmin::layouts.new_master')

@section('styles')
@endsection

@section('breadcrumbs')
	<!-- Breadcrumb -->
	<ol class="breadcrumb">
		<li class="breadcrumb-item active">SMS</li>
	</ol>
@endsection

@section('sidebar')
	<a class="btn btn-block btn-outline-primary"><i class="essential-xs essential-add mr-1"></i>Skicka SMS</a>
@endsection

@section('topbar')
	<div class="row">
		<div class="col-12 col-md-6">
			@include('rl_sms::admin.pages.sms.includes.filter')
		</div>
		<div class="col-4 d-flex">
			<div class="text-center mr-4" style="white-space: nowrap">
				<h6 class="mb-0">Använda SMS</h6>458/1500
			</div>
			<div class="text-center" style="white-space: nowrap">
				<h6 class="mb-0">Senast påfylld</h6> 2021-08-05
			</div>
		</div>
		@if(isset($sms) && !$sms->isEmpty())
			<div class="col-8 col-md-2 append-links">
				<span class="float-right">{{ $sms->links('rl_sms::admin.pages.sms.includes.pagination') }}</span>
			</div>
		@endif
	</div>
@endsection

@section('content')
	<div class="row">

{{--		<!-- Usage, Refill -->--}}
{{--		<div class="col-12 col-lg-3">--}}
{{--			<div class="card">--}}
{{--				<div class="card-body">--}}
{{--					<div class="row mb-2">--}}
{{--						<div class="col-6 col-lg-12 col-xl-6" style="white-space: nowrap">--}}
{{--							<h5 class="mb-0">Använda SMS:</h5>--}}
{{--						</div>--}}
{{--						<div class="col-6 col-lg-12 col-xl-6" style="white-space: nowrap">--}}
{{--							<h5 class="mb-0"><span class="font-weight-normal">458/1500</span></h5>--}}
{{--						</div>--}}
{{--					</div>--}}
{{--					<div class="row">--}}
{{--						<div class="col-6 col-lg-12 col-xl-6" style="white-space: nowrap">--}}
{{--							<h5 class="mb-0">Senast påfylld:</h5>--}}
{{--						</div>--}}
{{--						<div class="col-6 col-lg-12 col-xl-6" style="white-space: nowrap">--}}
{{--							<h5 class="mb-0"><span class="font-weight-normal">2021-08-05</span></h5>--}}
{{--						</div>--}}
{{--					</div>--}}
{{--				</div>--}}
{{--			</div>--}}
{{--		</div>--}}

		<!-- Chart -->
		<div class="col-12">
			<div class="card">
				<div class="card-body">
					<div>
						<canvas height="75" id="sms_chart"></canvas>
					</div>
				</div>
			</div>
		</div>

	</div>

	<!-- Table -->
	<div class="row">
		<div class="col-12 append-bookings-to">
			@include('rl_sms::admin.pages.sms.includes.table')
		</div>
	</div>
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

		//Function for fetching data and inserting it into chartjs
		function get_chart_data(chart){
			$.ajax({
				type: 'get',
				url: '{{ route('rl_sms.admin.sms.chart') }}',
				cache: false,
				dataType: 'json',
				data: {},
				beforeSend: function(){},
				success: function (data) {
					chart.data = data;
					chart.update();
				},
				error: function(xhr, textStatus, errorThrown){
					alert(JSON.stringify(xhr));
				}
			});
		}

		$(document).ready(function(){

			$('.go-to-url').on('click', function(e){
				if(!$(e.target).hasClass('dropdown-toggle') && !$(e.target).hasClass('do-delete-row')){
					goToURL = $(this).attr('data-url');
					window.location = goToURL;
				}
			});

			//Initializing chartjs
			let ctx 		= $('#sms_chart');
			let sms_chart 	= new Chart(ctx, {
				type: 'line',
				data: null,
				options: {
					scales: {
						y: {
							beginAtZero: true,
						}
					},
				}
			});

			//Fetching data for chartjs
			get_chart_data(sms_chart);

		});

	</script>

	@include('rl_sms::admin.pages.sms.scripts.filter')
@stop

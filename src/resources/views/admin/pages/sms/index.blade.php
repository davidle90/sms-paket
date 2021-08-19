@extends('rl_webadmin::layouts.new_master')

@section('styles')
	<style>
		.modal-backdrop{
			z-index: 1100 !important;
		}

		.modal-backdrop+.modal-backdrop {
			z-index: 1102 !important;
		}

		.modal-backdrop+.modal-backdrop+.modal-backdrop {
			z-index: 1104 !important;
		}

		.select2-selection__clear {
			margin-top: 0px;
		}

		.receiver-list > div:nth-child(2) {
			background-color: #f2f4f8;
		}

		.receiver-list > div:hover {
			background-color: #f5f5f5;
		}

		.iti__flag {background-image: url('/img/flags.png')}

		@media (-webkit-min-device-pixel-ratio: 2), (min-resolution: 192dpi) {
			.iti__flag {background-image: url('/img/flags@2x.png');}
		}

		.iti {
			width: 100%;
		}

		.is-invalid-bg {
			background-color: #ffe6e6 !important;
		}

		.is-invalid-border {
			border: 1px solid #ff5454 !important;
		}

	</style>
@endsection

@section('breadcrumbs')
	<!-- Breadcrumb -->
	<ol class="breadcrumb">
		<li class="breadcrumb-item active">SMS</li>
	</ol>
@endsection

@section('modals')
	@include('rl_sms::admin.pages.sms.modals.send')
	@include('rl_sms::admin.pages.sms.modals.receivers.modal')
	@include('rl_sms::admin.pages.sms.modals.receivers.import.modal')
	@include('rl_sms::admin.pages.sms.modals.receivers.remove.modal')
@endsection

@section('sidebar')
	<a class="btn btn-block btn-outline-primary" data-toggle="modal" data-target="#sendModal"><i class="essential-xs essential-add mr-1"></i>Skicka SMS</a>
@endsection

@section('topbar')
	<div class="row">
		<div class="col-12 col-md-6">
			@include('rl_sms::admin.pages.sms.includes.filter')
		</div>
		<div class="col-4 d-flex">
		</div>
		@if(isset($sms) && !$sms->isEmpty())
			<div class="col-8 col-md-2 append-links">
				<span class="float-right">{{ $sms->links('rl_sms::admin.pages.sms.includes.pagination') }}</span>
			</div>
		@endif
	</div>
@endsection

@section('content')
	<!-- Refill & used info -->
	<div class="row">
		@include('rl_sms::admin.pages.sms.includes.cards')
	</div>

	<!-- Chart -->
	<div class="row">
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
		<div class="col-12 append-items-to">
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
				data: {
					daterange: $('#filter-form input[name="daterange"]').val()
				},
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

			$('.select-sender').select2({
				dropdownParent: $('#sendModal'),
				placeholder: "Välj avsändare",
				allowClear: true
			});

			//Initializing chartjs
			let ctx 		= $('#sms_chart');
			let sms_chart 	= new Chart(ctx, {
				type: 'bar',
				data: null,
				options: {
					scales: {
						y: {
							beginAtZero: true,
						}
					},
					plugins: {
						tooltip: {
							filter: function (tooltipItem) {
								return tooltipItem.datasetIndex !== 2;
							}
						}
					}
				},
			});

			//Fetching data for chartjs
			get_chart_data(sms_chart);

			$('#filter-form input[name="daterange"]').on('change', function(){
				get_chart_data(sms_chart);
			});

			$('[data-toggle="tooltip"]').tooltip();

			$(document).on('click', '.doSendSMS', function(){
				console.log($('#send_form').serializeArray());
			});
		});

	</script>

	@include('rl_sms::admin.pages.sms.scripts.filter')
	@include('rl_sms::admin.pages.sms.modals.receivers.scripts')
	@include('rl_sms::admin.pages.sms.modals.script')
@stop

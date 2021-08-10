@extends('rl_webadmin::layouts.new_master')

@section('styles')
	<style>
		.modal-backdrop {
			z-index: 1100 !important;
		}

		.redactor-dropdown {
			z-index: 1102 !important;
		}

		#redactor-overlay {
			z-index: 1103 !important;
		}

		#redactor-modal {
			z-index: 1104 !important;
		}

		.receiver-list > div:nth-child(2) {
			background-color: #f2f4f8;
		}

		.receiver-list > div:hover {
			background-color: #f5f5f5;
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
			<div class="text-center mr-4" style="white-space: nowrap">
				<h6 class="mb-0">Använda SMS</h6> {{ $used_sms_quantity ?? ''}}
			</div>
			<div class="text-center" style="white-space: nowrap">
				<h6 class="mb-0">Senast påfylld</h6> {{ $latest_refill->created_at->copy()->format('Y-m-d') ?? '' }}
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

			$('.select2-receivers').select2({
			});

			$R('.redactor-message', {
				lang: 'sv',
				plugins: ['counter'],
				minHeight: '100px',
				maxHeight: '300px',
				formatting: ['p', 'blockquote'],
				buttons: ['redo', 'undo', 'bold', 'italic', 'underline', 'link', 'lists'],
				toolbarFixedTopOffset: 72, // pixel
				pasteLinkTarget: '_blank',
				linkNofollow: true,
				breakline: true,
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
				}
			});

			//Fetching data for chartjs
			get_chart_data(sms_chart);

			$('#filter-form input[name="daterange"]').on('change', function(){
				get_chart_data(sms_chart);
			});

			$('[data-toggle="tooltip"]').tooltip();
		});

	</script>

	@include('rl_sms::admin.pages.sms.scripts.filter')
@stop

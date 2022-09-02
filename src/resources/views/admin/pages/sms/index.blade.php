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
		<div class="col-2 d-flex">
		</div>
{{--		@if(isset($sms) && !$sms->isEmpty())--}}
{{--			<div class="col-8 col-md-2 append-links">--}}
{{--				<span class="float-right">{{ $sms->links('rl_sms::admin.pages.sms.includes.pagination') }}</span>--}}
{{--			</div>--}}
{{--		@endif--}}
		<div class="col-8 col-md-4">
			<span class="float-right">
				@include('rl_sms::admin.pages.sms.includes.month_switcher')
			</span>
		</div>
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

					<canvas height="75" id="sms_chart"></canvas>

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
					daterange: $('#filter-form input[name="daterange"]').val(),
					daterange_full: $('#filter-form input[name="daterange_full"]').val(),
				},
				beforeSend: function(){},
				success: function (data) {
					chart.data = data;
					chart.update();

					let total_sms = data.datasets[1].data.reduce((a, v) => a + v, 0);
					let total_msg = data.datasets[0].data.reduce((a, v) => a + v, 0);

					let start_date 	= moment(data.labels[0]).format('DD/MM');
					let end_date	= moment(data.labels[data.labels.length - 1]).format('DD/MM');

					$('.total-sms').html(`${ total_msg } st (${ total_sms } sms totalt)`);
				},
				error: function(xhr, textStatus, errorThrown){
					alert(JSON.stringify(xhr));
				}
			});
		}

		// Function to update daterange input
		function update_daterange($element, event_type) {
			let daterange 	= $element.val();
			let date_arr 	= daterange.split(' - ');

			if(date_arr[0] && date_arr[1]) {
				let starts_at	= date_arr[0];
				let ends_at		= date_arr[1];
				let new_daterange;

				switch(event_type) {
					case 'prev':
						starts_at 	= moment(starts_at).subtract(1, 'M');
						ends_at 	= moment(starts_at).endOf('month');
						break;
					case 'current':
						starts_at	= moment().startOf('month');
						ends_at 	= moment().endOf('month');
						break;
					case 'next':
						starts_at 	= moment(starts_at).add(1, 'M');
						ends_at 	= moment(starts_at).endOf('month');
						break;
				}

				new_daterange = starts_at.format('YYYY-MM-DD')+' - '+ends_at.format('YYYY-MM-DD');

				$element.val(new_daterange);
				$element.trigger('change');
				$('#search_btn').trigger('click');
				update_month_label(new_daterange);
			}
		}

		function update_month_label(daterange) {
			let monthNames = [
				"Januari",
				"Februari",
				"Mars",
				"April",
				"Maj",
				"Juni",
				"Juli",
				"Augusti",
				"September",
				"Oktober",
				"November",
				"December"
			];

			let date_arr  = daterange.split(' - ');
			let starts_at = date_arr[0];

			if(date_arr[0]) {
				let month_num 	= moment(starts_at).format('M');
				let month		= monthNames[month_num-1];
				let year		= moment(starts_at).format('YYYY');

				$('.append-current-month').html(monthNames[month_num-1]+' '+year);
			}
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
								if(tooltipItem.datasetIndex == 3 && tooltipItem.dataset.data[tooltipItem.dataIndex].nested.sum == 0) {
									return false
								}

								return true;
							},
							callbacks: {
								label: function(tooltipItem) {
									if(tooltipItem.datasetIndex == 3) {
										return tooltipItem.dataset.label + ' : ' + tooltipItem.dataset.data[tooltipItem.dataIndex].nested.sum + 'kr';
									}

									return tooltipItem.dataset.label + ' : ' + tooltipItem.dataset.data[tooltipItem.dataIndex] + 'st';
								}
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

			/*
			* Handle month pagination
			*/
			update_month_label($('#filter-form input[name="daterange"]').val());

			$('.onPrevMonth').on('click', function() {
				update_daterange($('#filter-form input[name="daterange"]'), 'prev');
			});

			$('.onCurrentMonth').on('click', function() {
				update_daterange($('#filter-form input[name="daterange"]'), 'current');
			});

			$('.onNextMonth').on('click', function() {
				update_daterange($('#filter-form input[name="daterange"]'), 'next');
			});
		});

	</script>

	@include('rl_sms::admin.pages.sms.scripts.filter')
	@include('rl_sms::admin.pages.sms.modals.receivers.scripts')
	@include('rl_sms::admin.pages.sms.modals.script')
@stop

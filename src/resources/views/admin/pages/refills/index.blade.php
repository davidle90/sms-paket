@extends('rl_webadmin::layouts.new_master')

@section('styles')
	<style>
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
		<li class="breadcrumb-item active">
			<a href="{{ route('rl_sms.admin.sms.index') }}"> SMS</a>
		</li>
		<li class="breadcrumb-item active">Påfyllningar</li>
	</ol>
@endsection

@section('modals')

@endsection

@section('sidebar')
	<a class="btn btn-block btn-outline-primary" href="{{ route('rl_sms.admin.sms.index') }}"><i class="fal fa-angle-left mr-1"></i> SMS</a>
@endsection

@section('topbar')
	<div class="row">
		<div class="col-12 col-md-6">
			@include('rl_sms::admin.pages.refills.includes.filter')
		</div>

		<div class="col-2 d-flex">
		</div>

		<div class="col-8 col-md-4">
			<span class="float-right">
				@include('rl_sms::admin.pages.refills.includes.month_switcher')
			</span>
		</div>
	</div>
@endsection

@section('content')

	<!-- Table -->
	<div class="row">
		<div class="col-12 append-items-to">
			@include('rl_sms::admin.pages.refills.includes.table')
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
		// Removes giver param from the url
		function removeURLParameter(url, parameter) {
			//prefer to use l.search if you have a location/link object
			var urlparts = url.split('?');
			if (urlparts.length >= 2) {

				var prefix = encodeURIComponent(parameter) + '=';
				var pars = urlparts[1].split(/[&;]/g);

				//reverse iteration as may be destructive
				for (var i = pars.length; i-- > 0;) {
					//idiom for string.startsWith
					if (pars[i].lastIndexOf(prefix, 0) !== -1) {
						pars.splice(i, 1);
					}
				}

				return urlparts[0] + (pars.length > 0 ? '?' + pars.join('&') : '');
			}
			return url;
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

				$('#filter-form input[name=daterange_full]').val('');
				$element.val(new_daterange);
				$element.trigger('change');
				$('#search_btn').trigger('click');
				update_month_label(new_daterange);

				// Remove page param from url
				if(window.location.href.indexOf('?page=') > 0) {
					window.history.pushState({}, null, removeURLParameter(window.location.href, 'page'));
				}
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

	@include('rl_sms::admin.pages.refills.scripts.filter')
@stop

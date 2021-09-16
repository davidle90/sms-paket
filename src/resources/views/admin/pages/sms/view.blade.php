@extends('rl_webadmin::layouts.new_master')

@section('styles')
	<style>
		.message-info td {
			border: none;
			padding: 0.25rem;
		}
	</style>
@endsection

@section('breadcrumbs')
	<!-- Breadcrumb -->
	<ol class="breadcrumb">
		<li class="breadcrumb-item"><a href="{{ route('rl_sms.admin.sms.index') }}">SMS</a></li>
		<li class="breadcrumb-item active">{{ $sms->receiver_title ?? '' }}</li>
	</ol>
@endsection

@section('modals')

@endsection

@section('sidebar')
	<a class="btn btn-block btn-outline-primary" href="{{ route('rl_sms.admin.sms.index') }}"><i class="fal fa-angle-left mr-2"></i> SMS</a>
@endsection

@php
	$SmsHelpers = new \Rocketlabs\Sms\App\Classes\Helpers;
@endphp

@section('content')
	<div class="card">
		<div class="card-header bold">
			Meddelandeinformation
		</div>

		<div class="card-body">
			<div class="row">
				<div class="col-12 col-sm-6">
					<table class="table message-info m-0">
						<tbody>
							<tr>
								<td class="w-25 bold">Avsändare</td>
								<td>{{ $sms->sender_title ?? '' }}</td>
							</tr>
							<tr>
								<td class="bold">Mottagare</td>
								@if(isset($sms->receiver_title) && !empty($sms->receiver_title))
									<td>{{ $sms->receiver_title ?? '' }}</td>
								@else
									<td class="text-danger">Saknas</td>
								@endif
							</tr>
							<tr>
								<td class="bold">Telefonnummer</td>
								<td>{{ $sms->receiver_phone ?? '' }}</td>
							</tr>
							<tr>
								<td class="bold">Land</td>
								<td>
									@if(isset($sms->country))
									<span
											class="flag-icon flag-icon-{{ $sms->country ?? '' }} mr-1"
											data-toggle="tooltip"
											data-placement="top"
											title="{{ country($sms->country)->getName() ?? '' }}"
									></span>
									{{ country($sms->country)->getName() ?? '' }}
									@endif
								</td>
							</tr>
						</tbody>
					</table>
				</div>

				<div class="col-12 col-sm-6">
					<table class="table message-info m-0">
						<tbody>
						<tr>
							<td class="w-25 bold">Operatör</td>
							@if(isset($sms->nexmo) && !$sms->nexmo->isEmpty() && !empty($sms->nexmo->first()->network))
								<td>{{ $mcc_mnc_list[$sms->nexmo->first()->network]['operator'] ?? '' }}</td>
							@else
								<td class="text-danger">Saknas</td>
							@endif
						</tr>
						<tr>
							<td class="bold">Skickat vid</td>
							<td>{{ $sms->sent_at->copy()->isoFormat('DD MMMM OY, HH:mm') ?? '' }}</td>
						</tr>
						<tr>
							<td class="bold">Antal sms</td>
							<td>{{ $sms->quantity ?? '' }}</td>
						</tr>
						<tr>

							@php
								$total_price = isset($sms->price_excl_vat) ? number_format($sms->price_excl_vat, 2, ',', ' ') : null;
							@endphp

							<td class="bold">Total pris</td>
							@if(isset($total_price))
								<td>  {{ $total_price }} SEK</td>
							@else
								<td class="text-danger">Saknas</td>
							@endif
						</tr>
						</tbody>
					</table>
				</div>

			</div>
		</div>
	</div>

	<div class="card">
		<div class="card-header bold">
			Meddelande
		</div>

		<div class="card-body message-info">
			@php
				if(isset($sms->receiver_title) && !empty($sms->receiver_title) && isset($sms->message->text)) {
                    $receiver_name      = explode(' ', $sms->receiver_title);
                    $message_formatted  = str_replace('%firstname%', trim($receiver_name[0] ?? '') , $sms->message->text);
                    $message_formatted  = str_replace('%lastname%', trim($receiver_name[1] ?? '') , $message_formatted);
                }

                if(isset($sms->variables) && !empty($sms->variables)) {
                    foreach($sms->variables as $key => $value){
                        $message_formatted = str_replace('%'.$key.'%', $value, $message_formatted ?? $sms->message->text ?? '');
                    }
                }
			@endphp

			@if(isset($message_formatted))
				<p>{{ $message_formatted }}</p>
			@elseif(isset($sms->message->text))
				<p>{{ $sms->message->text }}</p>
			@else
				<p class="text-danger">Saknas</p>
			@endif
		</div>

	</div>

	<div class="card">
		<div class="card-header bold" style="border-bottom: none;">
			SMS-information
		</div>

		<table class="table table-striped table-white table-outline table-hover mb-0 border-secondary" style="border: none;">
			<thead>
				<tr>
					<th style="padding-left: 1.25rem;">Meddelande ID</th>
					<th>Telefonnummer</th>
					<th>Operatör</th>
					<th>Pris</th>
					<th>Status</th>
				</tr>
			</thead>

			<tbody>
			@if(isset($sms->nexmo) && !$sms->nexmo->isEmpty())
				@foreach($sms->nexmo as $n)
					<tr>
						<td style="padding-left: 1.25rem;">{{ $n->message_id ?? 'Saknas' }}</td>
						<td>+{{ $n->to ?? 'Saknas' }}</td>
						<td>{{ $mcc_mnc_list[$n->network]['operator'] ?? 'Saknas' }}</td>
						<td>{{ (isset($sms->price_excl_vat) && isset($sms->quantity)) ? number_format(($sms->price_excl_vat / $sms->quantity), 2, ',', ' ') : 'N/A' }} SEK</td>

						@php
							$color_class = '';
							$status_text = 'Skickat';

							if(isset($n->request_id) && isset($n->status)) {

							    switch(rl_sms::getVerifyStatusString($n->status)) {
							        case $SmsHelpers::VERIFY_SUCCESS:
							            $color_class = 'text-success';
							            $status_text = 'Verifierad';
							            break;
									case $SmsHelpers::VERIFY_IN_PROGRESS:
										$color_class = 'text-primary';
										$status_text = 'Pågående';
										break;
									case $SmsHelpers::VERIFY_FAILED:
										$color_class = 'text-danger';
										$status_text = 'Misslyckad';
										break;
									case $SmsHelpers::VERIFY_EXPIRED:
										$color_class = 'text-danger';
										$status_text = 'Utgången';
										break;
									case $SmsHelpers::VERIFY_CANCELLED:
										$color_class = 'text-danger';
										$status_text = 'Avbruten';
									break;
							    }

							} elseif(isset($n->receipt->status)) {

							    switch($n->receipt->status) {
							        case 'accepted':
							            $color_class = 'text-primary';
							            $status_text = 'Accepterat';
							            break;
									case 'buffered':
										$color_class = 'text-info';
										$status_text = 'Buffrat';
									break;
							        case 'delivered':
							            $color_class = 'text-success';
							            $status_text = 'Levererat';
							            break;
									case 'failed':
									    $color_class = 'text-danger';
									    $status_text = 'Misslyckat';
									    break;
									case 'expired':
									    $color_class = 'text-danger';
									    $status_text = 'Utgånget';
									    break;
									case 'rejected':
										$color_class = 'text-danger';
										$status_text = 'Avvisat';
									break;
							    }

							}


						@endphp

						<td class="{{ $color_class ?? '' }}">{{ $status_text }}</td>
					</tr>
				@endforeach
			@endif
			</tbody>
		</table>

	</div>
@stop

@section('scripts')
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



@extends('rl_webadmin::layouts.new_master')

@section('styles')
    <style>

    </style>
@endsection

@section('breadcrumbs')
    <!-- Breadcrumb -->
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('rl_sms.admin.senders.index') }}">Avsändare</a></li>

        @if(isset($sender) && !empty($sender))
            <li class="breadcrumb-item active">{{ $sender->name ?? '' }}</li>
        @else
            <li class="breadcrumb-item active">Skapa ny</li>
        @endif

    </ol>
@endsection

@section('modals')
    @if(isset($sender) && !empty($sender))
        @include('rl_sms::admin.pages.senders.modals.drop')
    @endif
@endsection

@section('sidebar')
    <a class="btn btn-block btn-outline-primary" href="{{ route('rl_sms.admin.senders.index') }}"><i class="fal fa-angle-left mr-2"></i> Avsändare</a>
    <span class="doSaveSender btn btn-block btn-outline-success">Spara</span>
    @if(isset($sender) && !empty($sender))
        <span class="btn btn-block btn-outline-danger" data-toggle="modal" data-target="#dropSenderModal">Ta bort</span>
    @endif
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <b>@if(isset($sender) && !empty($sender)) Redigera avsändare @else Skapa avsändare @endif</b>
        </div>

        <div class="card-body collapse show" id="collapseAccount">
            <form id="sender_form" method="post" action="{{ route('rl_sms.admin.senders.store') }}" autocomplete="off">

                <input type="hidden" name="id" value="{{ $sender->id ?? '' }}" />



                <div class="row">
                    <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                        <!-- Name -->
                        <div class="mb-3 form-group">
                            <h6 class="bold">Namn</h6>
                            <input type="text" name="name" id="inputLabel" class="form-control" placeholder="" value="{{ $sender->name ?? '' }}">
                        </div>
                    </div>
                    <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                        <!-- Label -->
                        <div class="mb-3 form-group">
                            <h6 class="bold">Label</h6>
                            <input type="text" name="sms_label" id="inputSlug" class="form-control" placeholder="" value="{{ $sender->sms_label ?? '' }}">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                        <!-- Slug -->
                        <div class="mb-3 form-group">
                            <h6 class="bold">Slug</h6>
                            <input type="text" name="slug" class="form-control" placeholder="" value="{{ $sender->slug ?? '' }}">
                        </div>
                    </div>
                </div>

            </form>
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



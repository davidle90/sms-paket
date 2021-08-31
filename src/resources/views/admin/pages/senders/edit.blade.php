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
                            <h6 class="bold">Namn<i class="fa fa-asterisk required-marker" aria-hidden="true"></i></h6>
                            <input type="text" name="name" id="inputLabel" class="form-control" placeholder="" value="{{ $sender->name ?? '' }}">
                        </div>
                    </div>
                    <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                        <!-- Label -->
                        <div class="mb-3 form-group">
                            <h6 class="bold">Label<i class="fa fa-asterisk required-marker" aria-hidden="true"></i></h6>
                            <input type="text" name="sms_label" id="inputSlug" class="form-control" placeholder="" value="{{ $sender->sms_label ?? '' }}">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                        <!-- Slug -->
                        <div class="mb-3 form-group">
                            <h6 class="bold">Slug<i class="fa fa-asterisk required-marker" aria-hidden="true"></i></h6>
                            <input type="text" name="slug" class="form-control" placeholder="" value="{{ $sender->slug ?? '' }}">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12 col-md-6">
                        <div class="mb-3 form-group">
                            <h6 class="bold">Description</h6>
                            <textarea
                                    name="description"
                                    id="description"
                                    class="redactor-description form-control u-form__input"
                            >
                                {{ $sender->description ?? '' }}
                            </textarea>
                        </div>
                    </div>
                </div>

            </form>
        </div>
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

            $R('.redactor-description', {
                lang: 'sv',
                plugins: ['counter', 'fullscreen'],
                minHeight: '100px',
                maxHeight: '300px',
                formatting: ['p', 'blockquote'],
                buttons: ['redo', 'undo', 'bold', 'italic', 'underline', 'link', 'lists', 'fullscreen'],
                toolbarFixedTopOffset: 72, // pixel
                pasteLinkTarget: '_blank',
                linkNofollow: true,
                breakline: true,
            });

        });

    </script>

    @include('rl_sms::admin.pages.senders.scripts.store')
    @include('rl_sms::admin.pages.senders.scripts.drop')
@stop



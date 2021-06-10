@extends('rl_webadmin::layouts.new_master')

@section('styles')
    <style>
        .table-placeholder {
            background: #008FFA !important;
            opacity: 0.2 !important;
            height: 60px;
        }

        .field-placeholder {
            background: #008FFA !important;
            opacity: 0.2 !important;
        }
    </style>
@endsection

@section('breadcrumbs')
    <!-- Breadcrumb -->
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('rl_forms.admin.forms.index') }}">Formulär</a></li>

        @if(isset($form) && !empty($form))
            <li class="breadcrumb-item"><a href="{{ route('rl_forms.admin.forms.view', ['id' => $form->id]) }}">{{ $form->in($default_language)->label ?? '' }}</a></li>
            <li class="breadcrumb-item active">Redigera</li>
        @else
            <li class="breadcrumb-item active">Skapa formulär</li>
        @endif

    </ol>
@endsection

@section('sidebar')
    @if(isset($form) && !empty($form))
        <a class="btn btn-block btn-outline-primary" href="{{ route('rl_forms.admin.forms.view', ['id' => $form->id]) }}"><i class="fal fa-angle-left mr-2"></i> {{ $form->in($default_language)->label ?? '' }}</a>
    @else
        <a class="btn btn-block btn-outline-primary" href="{{ route('rl_forms.admin.forms.index') }}"><i class="fal fa-angle-left mr-2"></i> Formulär</a>
    @endif
    <span class="doSaveForm btn btn-block btn-outline-success">Spara</span>
    @if(isset($form) && !empty($form))
        <span class="doDropForm btn btn-block btn-outline-danger" data-id="{{ $form->id ?? '' }}">Radera</span>
    @endif
@endsection

@section('content')

    <div class="card">
        <div class="card-header">
            @if(isset($form) && !empty($form))
                <b>Redigera formulär</b>
            @else
                <b>Skapa formulär</b>
            @endif
        </div>

        <div class="card-body collapse show" id="collapseAccount">
            <form id="form_form" method="post" action="{{ route('rl_forms.admin.forms.store') }}" autocomplete="off">

                <input type="hidden" name="form_id" value="{{ $form->id ?? '' }}" />

                <h6 class="bold">Label</h6>

                @foreach($languages as $key => $lang)
                    <!-- Label -->
                    <div class="row">
                        <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                            <div class="mb-3 form-label-group form-group">
                                <input type="text" name="labels[{{ $key }}]" id="value_{{ $key ?? '' }}" class="form-control" placeholder="" value="{{ (isset($form)) ? $form->in($key)->label : '' }}">
                                <label for="value_{{ $key ?? '' }}">
                                    @ucfirst(language($key)->getNativeName()) ({{ language($key)->getName() }})
                                    @if($key == $default_language)
                                        <i class="fa fa-asterisk required-marker" aria-hidden="true"></i>
                                    @endif
                                </label>
                            </div>
                        </div>
                    </div>
                @endforeach

                <h6 class="bold">Inställningar</h6>

                <div class="row">
                    <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                        <!-- Slug -->
                        <div class="mb-3 form-label-group form-group">
                            <input type="text" name="slug" id="inputLabel" class="form-control" placeholder="" value="{{ $form->slug ?? '' }}">
                            <label for="inputLabel">Slug <i class="fa fa-asterisk required-marker" aria-hidden="true"></i></label>
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
        });

    </script>

    @include('rl_forms::admin.pages.forms.scripts.store')
    @include('rl_forms::admin.pages.forms.scripts.drop')

@stop


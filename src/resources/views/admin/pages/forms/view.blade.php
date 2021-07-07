@extends('rl_webadmin::layouts.new_master')

@section('styles')
    <link href="{{ mix('css/app/multiselect.css') }}" rel="stylesheet" type="text/css">
@endsection

@section('breadcrumbs')
    <!-- Breadcrumb -->
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('rl_forms.admin.forms.index') }}">Formulär</a></li>
        <li class="breadcrumb-item active">{{ $form->in($default_language ?? $fallback_language)->label ?? '' }}</li>
    </ol>
@endsection

@section('sidebar')
    <a class="btn btn-block btn-outline-primary" href="{{ route('rl_forms.admin.forms.index') }}"><i class="fal fa-angle-left mr-2"></i> Formulär</a>
    <a class="btn btn-block btn-outline-primary" href="{{ route('rl_forms.admin.forms.edit', ['id' => $form->id]) }}">Redigera formulär</a>
@endsection

@section('content')

    <div class="card">

        <div class="card-header">
            <b>{{ $form->in($default_language ?? $fallback_language)->label ?? '' }}</b>
        </div>

        <div class="card-body">

            <!-- Rendering form -->
            @include('rl_forms::admin.pages.forms.templates.form.view')

        </div>

    </div>

@stop

@section('scripts')
    <script src="{{ mix('js/app/multiselect.js') }}"></script>

    <script type="text/javascript">
        $(document).ready(function(){
            $('.go-to-url').on('click', function(e){
                if(!$(e.target).hasClass('dropdown-toggle') && !$(e.target).hasClass('do-delete-row')){
                    goToURL = $(this).attr('data-url');
                    window.location = goToURL;
                }
            });

            $('.select-single').select2({
                placeholder: "",
                allowClear: true,
                minimumResultsForSearch: -1
            });

            $('.select-multiple').multiselect({
                columns: 1,
                placeholder: "Välj alternativ",
                search: true,
                selectAll: true
            });

            $R('.redactor-textarea', {
                lang: 'sv',
                plugins: ['counter', 'fullscreen'],
                minHeight: '150px',
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
@stop


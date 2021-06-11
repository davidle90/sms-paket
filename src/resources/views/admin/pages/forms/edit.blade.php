@extends('rl_webadmin::layouts.new_master')

@section('styles')
    <style>
        .placeholder {
            background-color:#e8f5ff;
            border:2px dashed #319cd6;
            border-radius:4px;
            text-align:center;
            color:#43ace0;
            font-size:20px;
            font-weight:bold;
            margin-bottom:15px;
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
    <span class="doAddSection btn btn-block btn-outline-primary">
        <i class="essential-xs essential-add"></i> Lägg till sektion
    </span>
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

        <div class="card-body">
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

                <h6 class="bold">Slug</h6>

                <div class="row">
                    <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                        <!-- Slug -->
                        <div class="mb-3 form-label-group form-group">
                            <input type="text" name="slug" id="inputLabel" class="form-control" placeholder="" value="{{ $form->slug ?? '' }}">
                            <label for="inputLabel">Slug <i class="fa fa-asterisk required-marker" aria-hidden="true"></i></label>
                        </div>

                    </div>
                </div>

                @if(isset($form->sections) && !$form->sections->isEmpty())
                    <div class="sortable-sections">
                        @foreach($form->sections as $section_index => $section)
                            <div class="row" id="section_{{ $section_index }}">
                                <input
                                        type="hidden"
                                        min=1
                                        class="sortOrderUpdateSectionVal"
                                        value="{{ $section->sort_order  ?? ''}}"
                                        name="sections[{{ $section_index }}][sort_order]"
                                        id="sections_{{ $section_index }}_sort_order"
                                >

                                <div class="col-12">
                                    <div class="card">

                                        <div class="card-header handle-section" style="background-color: #e9f3fc; cursor: grabbing;">
                                            <b>Sektion <span class="sortOrderUpdateSectionLabel">{{ $section->sort_order ?? '' }}</span></b>

                                            <span class="float-right">
                                                <span class="m-0 mr-3 pointer">
                                                    <i class="essential-xs essential-add"></i> Lägg till fråga
                                                </span>
                                                <span class="m-0 pointer">
                                                    <i class="fal fa-pencil-alt"></i>
                                                </span>
                                            </span>
                                        </div>

                                        <div class="card-body sortable-elements" style="min-height: 150px;">
                                            <div id="filler_div"></div>
                                            @if(isset($section->elements) && !empty($section->elements))
                                                @foreach($section->elements as $element_index => $element)
                                                    <div class="row" id="element_{{ $element_index }}">
                                                        <input
                                                                type="hidden"
                                                                min=1
                                                                class="sortOrderUpdateElementVal"
                                                                value="{{ $section->sort_order  ?? ''}}"
                                                                name="sections[{{ $section_index }}][elements][{{ $element_index }}][sort_order]"
                                                                id="sections_{{ $section_index }}_elements_{{ $element_index }}_sort_order"
                                                        >

                                                        <div class="col-12">
                                                            <div class="card">

                                                                <div class="card-header handle-element" style="background-color: #dcefdc; cursor: grabbing;">
                                                                    <b>Fråga <span class="sortOrderUpdateElementLabel">{{ $element->pivot->sort_order ?? '' }}</span> - {{ $element->type->label ?? '' }}</b>

                                                                    <span class="float-right">
                                                                        <span class="m-0 pointer">
                                                                            <i class="fal fa-pencil-alt"></i>
                                                                        </span>
                                                                    </span>
                                                                </div>

                                                                <div class="card-body">

                                                                </div>

                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            @endif
                                        </div>

                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif

            </form>
        </div>

    </div>

    <!-- Template Section -->
    <div class="row hidden" id="template_section">
        <input
                type="hidden"
                min=1
                class="sortOrderUpdateSectionVal"
                value=""
                name=""
                id=""
        >

        <div class="col-12">
            <div class="card">

                <div class="card-header handle-section" style="background-color: #e9f3fc; cursor: grabbing;">
                    <b>Sektion <span class="sortOrderUpdateSectionLabel"></span></b>

                    <span class="float-right">
                        <span class="m-0 mr-3 pointer">
                            <i class="essential-xs essential-add"></i> Lägg till fråga
                        </span>
                        <span class="m-0 pointer">
                            <i class="fal fa-pencil-alt"></i>
                        </span>
                    </span>
                </div>

                <div class="card-body sortable-elements" style="min-height: 150px;">
                    <div id="filler_div"></div>
                </div>

            </div>
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

            function init_drag_drop() {
                $('.sortable-sections').sortable({
                    animation: 150, // default: 0
                    axis: "y",
                    handle: ".handle-section",
                    cursor: "move",
                    placeholder: 'placeholder',
                    forcePlaceholderSize: true,
                    connectWith: '',
                    start: function start(e, ui) {

                    },
                    update: function (event, ui) {
                        let sort_order = 1;

                        $(this).children('div').each(function() {
                            $(this).find('.sortOrderUpdateSectionVal').val(sort_order);
                            $(this).find('.sortOrderUpdateSectionLabel').text(sort_order);

                            sort_order++;
                        });
                    }
                });

                $('.sortable-elements').sortable({
                    animation: 150, // default: 0
                    axis: "y",
                    handle: ".handle-element",
                    cursor: "move",
                    placeholder: 'placeholder',
                    forcePlaceholderSize: true,
                    connectWith: '.sortable-elements',
                    start: function start(e, ui) {

                    },
                    update: function (event, ui) {
                        let sort_order = 1;

                        $(this).children('div').each(function() {
                            if($(this).is('#filler_div')) return;

                            $(this).find('.sortOrderUpdateElementVal').val(sort_order);
                            $(this).find('.sortOrderUpdateElementLabel').text(sort_order);

                            sort_order++;
                        });
                    }
                });
            }

            $('.doAddSection').on('click', function(){
                let $template = $('#template_section').clone();

                $template.attr('id', '');
                $template.removeClass('hidden');
                $template.appendTo('.sortable-sections');

                init_drag_drop();
            });

            init_drag_drop();
        });

    </script>

    @include('rl_forms::admin.pages.forms.scripts.store')
    @include('rl_forms::admin.pages.forms.scripts.drop')

@stop


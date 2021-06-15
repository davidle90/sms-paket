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

@section('modals')

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
                                <!-- Hidden inputs start -->
                                <input
                                        type="hidden"
                                        min=1
                                        class="sortOrderUpdateSectionVal"
                                        value="{{ $section->sort_order  ?? ''}}"
                                        name="sections[{{ $section_index }}][sort_order]"
                                        id="sections_{{ $section_index }}_sort_order"
                                >
                                <!-- Hidden inputs end -->

                                @include('rl_forms::admin.pages.forms.modals.section')
                                @include('rl_forms::admin.pages.forms.modals.types')

                                <div class="col-12">
                                    <div class="card">

                                        <div class="card-header handle-section" style="background-color: #e9f3fc; cursor: grabbing;">
                                            <b>Sektion <span class="sortOrderUpdateSectionLabel">{{ $section->sort_order ?? '' }}</span></b>

                                            <span class="float-right">
                                                <span class="m-0 mr-3 pointer" data-toggle="modal" data-target="#chooseTypeModal_{{ $section_index }}" data-index="{{ $section_index }}">
                                                    <i class="essential-xs essential-add"></i> Lägg till fråga
                                                </span>
                                                <span class="m-0 pointer" data-toggle="modal" data-target="#editSectionModal_{{ $section_index }}" data-index="{{ $section_index }}">
                                                    <i class="fal fa-pencil-alt"></i>
                                                </span>
                                            </span>
                                        </div>

                                        <div class="card-body">
                                            <h6 class="bold section-label">{{ $section->in($key)->label ?? '' }}</h6>
                                            <p><i class="text-danger section-description">{{ $section->in($key)->description ?? '' }}</i></p>
                                            <div class="sortable-elements" style="min-height: 100px;">
                                                <input class="section-index" type="hidden" value="{{ $section_index }}">
                                                <div id="filler_div"></div>
                                                <span class="insert-create-element"></span>

                                                @if(isset($section->elements) && !empty($section->elements))
                                                    @foreach($section->elements as $element_index => $element)

                                                        <div class="row" id="element_{{ $element_index }}">
                                                            <input
                                                                    type="hidden"
                                                                    min=1
                                                                    class="sortOrderUpdateElementVal"
                                                                    value="{{ $element->pivot->sort_order ?? '' }}"
                                                                    name="sections[{{ $section_index }}][elements][{{ $element_index }}][sort_order]"
                                                                    id="sections_{{ $section_index }}_elements_{{ $element_index }}_sort_order"
                                                            >

                                                            @include('rl_forms::admin.pages.forms.modals.element')

                                                            <div class="col-12">
                                                                <div class="card">

                                                                    <div class="card-header handle-element" style="background-color: #dcefdc; cursor: grabbing;">
                                                                        <b>Fråga <span class="sortOrderUpdateElementLabel">{{ $element->pivot->sort_order ?? '' }}</span> - {{ $element->type->label ?? '' }}</b>

                                                                        <span class="float-right">
                                                                        <span
                                                                            class="m-0 pointer element-modal-button"
                                                                            data-toggle="modal"
                                                                            data-target="#elementEditModal_section_{{ $section_index }}_element_{{ $element_index }}"
                                                                            data-section-index="{{ $section_index }}"
                                                                            data-element-index="{{ $element_index }}"
                                                                        >
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
                            </div>
                        @endforeach
                    </div>
                @endif

            </form>
        </div>

    </div>

    <!-- Template Section -->
    <div class="row hidden" id="template_section">
        <!-- Hidden inputs -->
        <input
                type="hidden"
                min=1
                class="sortOrderUpdateSectionVal"
                value=""
                name=""
                id=""
        >

        @include('rl_forms::admin.pages.forms.modals.templates.section')
        @include('rl_forms::admin.pages.forms.modals.templates.types')

        <div class="col-12">
            <div class="card">

                <div class="card-header handle-section" style="background-color: #e9f3fc; cursor: grabbing;">
                    <b>Sektion <span class="sortOrderUpdateSectionLabel"></span></b>

                    <span class="float-right">
                        <span class="m-0 mr-3 pointer section-types-button" data-toggle="modal" data-target="">
                            <i class="essential-xs essential-add"></i> Lägg till fråga
                        </span>
                        <span class="m-0 pointer section-modal-button" data-toggle="modal" data-target="">
                            <i class="fal fa-pencil-alt"></i>
                        </span>
                    </span>
                </div>

                <div class="card-body">
                    <h6 class="bold section-label"></h6>
                    <p><i class="text-danger section-description"></i></p>

                    <div class="sortable-elements" style="min-height: 100px;">
                        <input class="section-index" type="hidden" value="">
                        <div id="filler_div"></div>
                        <span class="insert-create-element"></span>

                    </div>
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

            $R('.redactor-sv', {
                lang: 'sv',
                plugins: ['counter', 'fullscreen'],
                minHeight: '100px',
                maxHeight: '300px',
                formatting: ['p', 'blockquote'],
                buttons: ['redo', 'undo', 'bold', 'italic', 'underline', 'lists', 'fullscreen'],
                toolbarFixedTopOffset: 72, // pixel
                pasteLinkTarget: '_blank',
                linkNofollow: true,
                breakline: true,
            });

            $R('.redactor-en', {
                lang: 'en',
                plugins: ['counter', 'fullscreen'],
                minHeight: '100px',
                maxHeight: '300px',
                formatting: ['p', 'blockquote'],
                buttons: ['redo', 'undo', 'bold', 'italic', 'underline', 'lists', 'fullscreen'],
                toolbarFixedTopOffset: 72, // pixel
                pasteLinkTarget: '_blank',
                linkNofollow: true,
                breakline: true,
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
                        let section_index = $(this).find('.section-index').val();

                        $(this).children('div').each(function() {
                            if($(this).is('#filler_div')) return;

                            let $sort_order_val     = $(this).find('.sortOrderUpdateElementVal');
                            let $sort_order_label   = $(this).find('.sortOrderUpdateElementLabel');
                            let $modal              = $(this).find('.element-modal-edit');
                            let $modal_button       = $(this).find('.element-modal-button');

                            $(this).attr('id', `element_${ sort_order - 1 }`);
                            $sort_order_label.text(sort_order);
                            $sort_order_val.val(sort_order);
                            $sort_order_val.attr('name', `sections[${ section_index }][elements][${ sort_order - 1 }][sort_order]`);
                            $modal.attr('id', `elementEditModal_section_${ section_index }_element_${ sort_order - 1 }`);
                            $modal.find('.modal-title').attr('id', `elementEditModalLabel_section_${ section_index }_element_${ sort_order - 1 }`);
                            $modal_button.attr('data-target', `#elementEditModal_section_${ section_index }_element_${ sort_order - 1 }`);
                            $modal_button.attr('data-section-index', section_index);
                            $modal_button.attr('data-element-index', sort_order - 1);

                            sort_order++;
                        });
                    }
                });
            }

            //Add new section
            $('.doAddSection').on('click', function(){
                let $template   = $('#template_section').clone();
                let $modal      = $template.find('#editSectionModal_template');
                let $modal_type = $template.find('#chooseTypeModal_template');
                let count       = $('.sortable-sections').children('div').length;

                //Section
                $template.find('.sortOrderUpdateSectionVal').val(count + 1);
                $template.find('.sortOrderUpdateSectionLabel').text(count + 1);
                $template.find('.section-index').val(count);
                $template.attr('id', 'section_' + count);
                $template.attr('name', `sections[${ count }][sort_order]`);
                $template.find('.section-modal-button').attr('data-target', `#editSectionModal_${ count }`);
                $template.find('.section-types-button').attr('data-target', `#chooseTypeModal_${ count }`);
                $template.removeClass('hidden');
                $template.appendTo('.sortable-sections');

                //Section edit modal
                $modal.attr('id', `editSectionModal_${ count }`);
                $modal.find('.doUpdateSection').attr('data-section-index', count);
                $modal.find('#editSectionModalLabel_template').attr('id', `editSectionModalLabel_${ count }`);
                $modal.find('.section-modal-labels').each(function() {
                    let iso = $(this).find('input').val();

                    $(this).find('input').attr('id', `section_${ count }_label_${ iso }`);
                    $(this).find('input').attr('name', `sections[${ count }][labels][${ iso }]`);
                    $(this).find('label').attr('for', `section_${ count }_label_${ iso }`);
                    $(this).find('input').val('');
                });
                $modal.find('.section-modal-description-textareas').each(function() {
                   let iso = $(this).find('input').val();

                   $(this).find('textarea').attr('id', `section_${ count }_description_${ iso }`);
                   $(this).find('textarea').attr('name', `sections[${ count }][descriptions][${ iso }]`);
                   $(this).find('textarea').addClass(`redactor-${ iso }`);

                   $R(`#section_${ count }_description_${ iso }`, {
                       lang: iso,
                       plugins: ['counter', 'fullscreen'],
                       minHeight: '100px',
                       maxHeight: '300px',
                       formatting: ['p', 'blockquote'],
                       buttons: ['redo', 'undo', 'bold', 'italic', 'underline', 'lists', 'fullscreen'],
                       toolbarFixedTopOffset: 72, // pixel
                       pasteLinkTarget: '_blank',
                       linkNofollow: true,
                       breakline: true,
                   });
                });

                //Section type modal
                $modal_type.attr('id', `chooseTypeModal_${ count }`);
                $modal_type.find('.doChooseType').attr('data-section-index', count);
                $modal_type.find('#chooseTypeModalLabel_template').attr('id', `chooseTypeModalLabel_${ count }`);

                init_drag_drop();
            });

            //Update section label and description on the card
            $(document).on('click', '.doUpdateSection', function(){
                let index   = $(this).attr('data-section-index');
                let label   = $(`#section_${ index }_label_{{ $default_language }}`).val();
                let text    = $R(`#section_${ index }_description_{{ $default_language }}`, 'source.getCode');

                $(`#section_${ index }`).find('.section-label').text(label);
                $(`#section_${ index }`).find('.section-description').text(text);
            });

            //On type pick
            $(document).on('click', '.doChooseType', function(){
                let type_id         = $(this).attr('data-type-id');
                let type_label      = $(this).attr('data-type-label');
                let section_index   = $(this).attr('data-section-index');

                $.ajax({
                    url: '{{ route('rl_forms.admin.forms.element.modal') }}',
                    data: {
                        "type_id": type_id,
                        "type_label": type_label,
                        "section_index": section_index
                    },
                    cache: false,
                    success: function(res) {

                        $(`#section_${ section_index }`).find('.insert-create-element').html(res);

                        $(`#chooseTypeModal_${ section_index }`).on('hidden.bs.modal', function () {
                            $(`#elementEditModal_section_${ section_index }_element_create`).modal('show');

                            $(`#chooseTypeModal_${ section_index }`).off('hidden.bs.modal');
                        });

                        $('.create-element-modal-textareas').each(function(){
                            let iso = $(this).find('input').val();

                            $R(`#section_${ section_index }_element_create_description_${ iso }`, {
                                lang: iso,
                                plugins: ['counter', 'fullscreen'],
                                minHeight: '100px',
                                maxHeight: '300px',
                                formatting: ['p', 'blockquote'],
                                buttons: ['redo', 'undo', 'bold', 'italic', 'underline', 'lists', 'fullscreen'],
                                toolbarFixedTopOffset: 72, // pixel
                                pasteLinkTarget: '_blank',
                                linkNofollow: true,
                                breakline: true,
                            });
                        });
                    }
                })
            });

            init_drag_drop();
        });

    </script>

    @include('rl_forms::admin.pages.forms.scripts.store')
    @include('rl_forms::admin.pages.forms.scripts.drop')

@stop


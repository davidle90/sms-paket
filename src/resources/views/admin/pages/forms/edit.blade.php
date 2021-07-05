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

    <link href="{{ mix('css/app/multiselect.css') }}" rel="stylesheet" type="text/css">
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
        <span class="btn btn-block btn-outline-danger" data-toggle="modal" data-target="#deleteFormModal">Radera</span>
    @endif
@endsection

@section('modals')
    @include('rl_forms::admin.pages.forms.modals.delete_form')
    @include('rl_forms::admin.pages.forms.modals.delete_section')
    @include('rl_forms::admin.pages.forms.modals.delete_element')
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
                               <input type="text" name="labels[{{ $key }}]" id="value_{{ $key ?? '' }}" class="form-control" placeholder="" value="{{ (isset($form)) ? $form->in($key)->label ?? '' : '' }}">
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
                       <div class="mb-3 form-group">
                           <input type="text" name="slug" id="inputLabel" class="form-control" placeholder="" value="{{ $form->slug ?? '' }}">
                       </div>

                   </div>
               </div>

               <div class="sortable-sections">
                    @if(isset($form->sections) && !$form->sections->isEmpty())
                       @foreach($form->sections as $section_index => $section)
                           <div class="row" id="section_{{ $section_index }}" data-element-count="{{ (isset($section->elements)) ? $section->elements->count() : 0 }}">
                               <!-- Hidden inputs start -->
                               <input
                                       type="hidden"
                                       min=1
                                       class="sortOrderUpdateSectionVal"
                                       value="{{ $section->sort_order  ?? ''}}"
                                       name="sections[{{ $section_index }}][sort_order]"
                                       id="sections_{{ $section_index }}_sort_order"
                               >
                               <input
                                        type="hidden"
                                        class="section-id"
                                        value="{{ $section->id }}"
                                        name="sections[{{ $section_index }}][id]"
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
                                           <h6 class="bold section-label">{{ $section->in($default_language)->label ?? '' }}</h6>
                                           <p class="section-description">{{ $section->in($default_language)->description ?? '' }}</p>
                                           <div class="sortable-elements" style="min-height: 100px;">
                                               <input class="section-index" type="hidden" value="{{ $section_index }}">
                                               <div id="filler_div"></div>

                                               @if(isset($section->elements) && !empty($section->elements))
                                                   @foreach($section->elements as $element_index => $element)

                                                       <div class="row element-wrapper" id="section_{{ $section_index }}_element_{{ $element_index }}">
                                                           <input
                                                                   type="hidden"
                                                                   min=1
                                                                   class="sortOrderUpdateElementVal"
                                                                   value="{{ $element->pivot->sort_order ?? '' }}"
                                                                   name="sections[{{ $section_index }}][elements][{{ $element_index }}][sort_order]"
                                                                   id="sections_{{ $section_index }}_elements_{{ $element_index }}_sort_order"
                                                           >
                                                           <input type="hidden" name="sections[{{ $section_index }}][elements][{{ $element_index }}][type_id]" value="{{ $element->type->id}}" class="element-type-id">
                                                           <input
                                                                   type="hidden"
                                                                   class="element-id"
                                                                   value="{{ $element->id }}"
                                                                   name="sections[{{ $section_index }}][elements][{{ $element_index }}][id]"
                                                           >

                                                           @include('rl_forms::admin.pages.forms.modals.element')

                                                           <div class="col-12">
                                                               <div class="card">

                                                                   <div class="card-header handle-element" style="background-color: #dcefdc; cursor: grabbing;">
                                                                       <b>Fråga
                                                                           <span class="sortOrderUpdateElementLabel">{{ $element->pivot->sort_order ?? '' }}</span> -
                                                                           {{ $element->type->label ?? '' }}
                                                                       </b>

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

                                                                   <div class="card-body update-card-body">
                                                                       <div class="mb-2">
                                                                           <h6 class="mb-0 element-label">
                                                                               {{ $element->in($default_language)->label ?? '' }}
                                                                               @if($element->pivot->required == 1 && isset($element->in($default_language)->label)) <i class="fa fa-asterisk required-marker" aria-hidden="true"></i> @endif
                                                                           </h6>
                                                                           @if(isset($element->in($default_language ?? $fallback_language)->description))
                                                                               <p class="element-description mb-0">{{ $element->in($default_language)->description ?? '' }}</p>
                                                                           @endif
                                                                       </div>

                                                                       <!-- Input -->
                                                                       @if($element->type_id === 1)
                                                                           <div class="row">
                                                                               <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                                                                                   <div class="form-group mb-1">
                                                                                       <input
                                                                                               type="text"
                                                                                               id="section_{{ $section_index }}_element_{{ $element_index }}_options_display"
                                                                                               class="form-control update-option"
                                                                                       >
                                                                                       <span><i class="text-danger element-required-text">{{ (isset($element->in($default_language)->required)) ? '*'.$element->in($default_language)->required : '' }}</i></span>
                                                                                   </div>
                                                                               </div>
                                                                           </div>
                                                                       @endif

                                                                       <!-- Textarea -->
                                                                       @if($element->type_id === 6)
                                                                           <div class="row">
                                                                               <div class="col-12 col-sm-6 col-md-4 col-lg-4">
                                                                                   <div class="form-group mb-1">
                                                                                       <textarea
                                                                                               id="section_{{ $section_index }}_element_{{ $element_index }}_options_display"
                                                                                               class="redactor-sv form-control u-form__input update-option"
                                                                                       ></textarea>
                                                                                       <span><i class="text-danger element-required-text">{{ (isset($element->in($default_language)->required)) ? '*'.$element->in($default_language)->required : '' }}</i></span>
                                                                                   </div>
                                                                               </div>
                                                                           </div>
                                                                       @endif

                                                                       <!-- Options -->
                                                                       @if((isset($element->options) && !$element->options->isEmpty()) || isset($element->table_id))
                                                                           <!-- Dropdown, Dropdown, multiselect-->
                                                                           @if($element->type_id === 2 || $element->type_id === 3)
                                                                               <div class="row">
                                                                                   <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                                                                                       <select
                                                                                               id="section_{{ $section_index }}_element_{{ $element_index }}_options_display"
                                                                                               class="form-control update-option"
                                                                                       >
                                                                                           <option></option>
                                                                                           <!-- Table data -->
                                                                                           @if(isset($element->table->data))
                                                                                               @foreach($element->table->data as $data)
                                                                                                   <option>{{ $data->in($default_language)->text ?? '' }}</option>
                                                                                               @endforeach
                                                                                           @endif
                                                                                           <!-- Option data -->
                                                                                           @foreach($element->options as $option)
                                                                                               <option>{{ $option->in($default_language)->label ?? '' }}</option>
                                                                                           @endforeach
                                                                                       </select>
                                                                                       <span><i class="text-danger element-required-text">{{ (isset($element->in($default_language)->required)) ? '*'.$element->in($default_language)->required : '' }}</i></span>
                                                                                   </div>
                                                                               </div>
                                                                           @endif

                                                                           <!-- Checkbox -->
                                                                           @if($element->type_id === 4)
                                                                               <div class="row">
                                                                                   <!-- Table data -->
                                                                                   @if(isset($element->table->data))
                                                                                       @foreach($element->table->data as $data_index => $data)
                                                                                           <div class="{{ (isset($element->alignment) && $element->alignment === 'horizontal') ? 'col-12 col-sm-6 col-md-4 col-lg-3' : 'col-12' }}">
                                                                                               <div class="custom-control custom-checkbox d-flex align-items-center">
                                                                                                   <input
                                                                                                           type="checkbox"
                                                                                                           class="custom-control-input update-data"
                                                                                                           id="section_{{ $section_index }}_element_{{ $element_index }}_table_data_display_{{ $data_index }}"
                                                                                                           disabled
                                                                                                   >
                                                                                                   <label class="custom-control-label" for="section_{{ $section_index }}_element_{{ $element_index }}_table_data_display_{{ $data_index }}">
                                                                                                       {{ $data->in($default_language)->text ?? '' }}
                                                                                                   </label>
                                                                                               </div>
                                                                                           </div>
                                                                                       @endforeach
                                                                                   @endif
                                                                                   <!-- Option data -->
                                                                                   @foreach($element->options as $option_index => $option)
                                                                                       <div class="{{ (isset($element->alignment) && $element->alignment === 'horizontal') ? 'col-12 col-sm-6 col-md-4 col-lg-3' : 'col-12' }}">
                                                                                           <div class="custom-control custom-checkbox d-flex align-items-center">
                                                                                               <input
                                                                                                       type="checkbox"
                                                                                                       class="custom-control-input update-option"
                                                                                                       id="section_{{ $section_index }}_element_{{ $element_index }}_options_display_{{ $option_index }}"
                                                                                                       disabled
                                                                                               >
                                                                                               <label class="custom-control-label" for="section_{{ $section_index }}_element_{{ $element_index }}_options_display_{{ $option_index }}">
                                                                                                   {{ $option->in($default_language)->label ?? '' }}
                                                                                               </label>
                                                                                           </div>
                                                                                       </div>
                                                                                   @endforeach
                                                                               </div>
                                                                               <div class="mt-1"><i class="text-danger element-required-text">{{ (isset($element->in($default_language)->required)) ? '*'.$element->in($default_language)->required : '' }}</i></div>
                                                                           @endif

                                                                           <!-- Radio -->
                                                                           @if($element->type_id === 5)
                                                                               <div class="row">
                                                                                   <!-- Table data -->
                                                                                   @if(isset($element->table->data))
                                                                                       @foreach($element->table->data as $data_index => $data)
                                                                                           <div class="{{ (isset($element->alignment) && $element->alignment === 'horizontal') ? 'col-12 col-sm-6 col-md-4 col-lg-3' : 'col-12' }}">
                                                                                               <div class="custom-control custom-radio">
                                                                                                   <input
                                                                                                           type="radio"
                                                                                                           class="custom-control-input"
                                                                                                           id="section_{{ $section_index }}_element_{{ $element_index }}_table_data_display_{{ $data_index }}"
                                                                                                           disabled
                                                                                                   >
                                                                                                   <label class="custom-control-label" for="section_{{ $section_index }}_element_{{ $element_index }}_table_data_display_{{ $data_index }}">
                                                                                                       {{ $data->in($default_language)->text ?? '' }}
                                                                                                   </label>
                                                                                               </div>
                                                                                           </div>
                                                                                       @endforeach
                                                                                   @endif
                                                                                   <!-- Option data -->
                                                                                   @foreach($element->options as $option_index => $option)
                                                                                       <div class="{{ (isset($element->alignment) && $element->alignment === 'horizontal') ? 'col-12 col-sm-6 col-md-4 col-lg-3' : 'col-12' }}">
                                                                                           <div class="custom-control custom-radio">
                                                                                               <input
                                                                                                       type="radio"
                                                                                                       class="custom-control-input"
                                                                                                       id="section_{{ $section_index }}_element_{{ $element_index }}_options_display_{{ $option_index }}"
                                                                                                       disabled
                                                                                               >
                                                                                               <label class="custom-control-label" for="section_{{ $section_index }}_element_{{ $element_index }}_options_display_{{ $option_index }}">
                                                                                                   {{ $option->in($default_language)->label ?? '' }}
                                                                                               </label>
                                                                                           </div>
                                                                                       </div>
                                                                                   @endforeach
                                                                               </div>
                                                                               <div class="mt-1"><i class="text-danger element-required-text">{{ (isset($element->in($default_language)->required)) ? '*'.$element->in($default_language)->required : '' }}</i></div>
                                                                           @endif

                                                                       @endif

                                                                       <!-- Imported Table -->
                                                                       @if(isset($element->table_id))
                                                                           <p class="mt-3 mb-0"><span class="bold">Importerad tabell:</span> {{ $element->table->label }}</p>
                                                                       @endif
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
                    @endif
               </div>
           </form>
       </div>

   </div>

   <!-- Template Section -->
   <div class="row hidden" id="template_section" data-element-count="{{ 0 }}">
       <!-- Hidden inputs -->
       <input
               type="hidden"
               min=1
               class="sortOrderUpdateSectionVal"
               value=""
               name=""
               id=""
       >
       <input
               type="hidden"
               class="section-id"
               value=""
               name=""
       >

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
                   <p class="section-description"></p>

                   <div class="sortable-elements" style="min-height: 100px;">
                       <input class="section-index" type="hidden" value="">
                       <div id="filler_div"></div>

                   </div>
               </div>

           </div>
       </div>
   </div>
@stop

@section('scripts')

   <script type="text/javascript">

       $(document).ready(function(){
           let section_count = $('.sortable-sections').children('div').length;

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
               buttons: ['redo', 'undo', 'bold', 'italic', 'underline', 'link', 'lists', 'fullscreen'],
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
               buttons: ['redo', 'undo', 'bold', 'italic', 'underline', 'link', 'lists', 'fullscreen'],
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
                       let sort_order       = 1;
                       let sort_order_radio = 1;
                       let section_index    = $(this).find('.section-index').val();

                       $(this).children('div').each(function() {
                           if($(this).is('#filler_div')) return;

                           let $sort_order_val     = $(this).find('.sortOrderUpdateElementVal');
                           let $sort_order_label   = $(this).find('.sortOrderUpdateElementLabel');
                           let $type_id            = $(this).find('.element-type-id');
                           let $element_id         = $(this).find('.element-id');
                           let $modal              = $(this).find('.element-modal-edit');
                           let $modal_button       = $(this).find('.element-modal-button');

                           //Element
                           $(this).attr('id', `section_${ section_index }_element_${ sort_order - 1 }`);
                           $sort_order_label.text(sort_order);
                           $sort_order_val.val(sort_order);
                           $sort_order_val.attr('name', `sections[${ section_index }][elements][${ sort_order - 1 }][sort_order]`);
                           $type_id.attr('name', `sections[${ section_index }][elements][${ sort_order - 1 }][type_id]`);
                           $element_id.attr('name', `sections[${ section_index }][elements][${ sort_order - 1 }][id]`);
                           $modal_button.attr('data-target', `#elementEditModal_section_${ section_index }_element_${ sort_order - 1 }`);
                           $modal_button.attr('data-section-index', section_index);
                           $modal_button.attr('data-element-index', sort_order - 1);

                           //Edit Modal
                           $modal.attr('id', `elementEditModal_section_${ section_index }_element_${ sort_order - 1 }`);
                           $modal.find('.modal-title').attr('id', `elementEditModalLabel_section_${ section_index }_element_${ sort_order - 1 }`);
                           $modal.find('.modal-body').attr('id', `elementEditModal_section_${ section_index }_element_${ sort_order - 1 }_body`);
                           $modal.find('.doUpdateElement').attr('data-section-index', section_index);
                           $modal.find('.doUpdateElement').attr('data-element-index', sort_order - 1);

                          //Edit Modal - Label, Textareas, Required boolean/text
                          $modal.find('.element-modal-labels').each(function() {
                              let iso = $(this).find('.element-modal-labels-iso').val();

                              $(this).find('.element-modal-labels-input').attr('id', `section_${ section_index }_element_${ sort_order - 1 }_label_${ iso }`);
                              $(this).find('.element-modal-labels-input').attr('name', `sections[${ section_index }][elements][${ sort_order - 1 }][labels][${ iso }]`);
                              $(this).find('label').attr('for', `section_${ section_index }_element_${ sort_order - 1 }_label_${ iso }`);
                          });

                           $modal.find('.element-modal-textareas').each(function() {
                               let iso = $(this).find('input').val();

                               $(this).find('textarea').attr('id', `section_${ section_index }_element_${ sort_order - 1 }_description_${ iso }`);
                               $(this).find('textarea').attr('name', `sections[${ section_index }][elements][${ sort_order - 1 }][descriptions][${ iso }]`);
                               $(this).find('label').attr('for', `section_${ section_index }_element_${ sort_order - 1 }_description_${ iso }`);
                           });

                           $modal.find('.element-modal-required-text').each(function() {
                               let iso = $(this).find('.element-modal-required-text-iso').val();

                               $(this).find('.element-modal-required-text-input').attr('id', `section_${ section_index }_element_${ sort_order - 1 }_required_text_${ iso }`);
                               $(this).find('.element-modal-required-text-input').attr('name', `sections[${ section_index }][elements][${ sort_order - 1 }][required_texts][${ iso }]`);
                               $(this).find('label').attr('for', `section_${ section_index }_element_${ sort_order - 1 }_required_text_${ iso }`);
                           });

                           $modal.find('.element-modal-required-checkbox input').attr('id', `section_${ section_index }_element_${ sort_order - 1 }_required`);
                           $modal.find('.element-modal-required-checkbox input').attr('name', `sections[${ section_index }][elements][${ sort_order - 1 }][required]`);
                           $modal.find('.element-modal-required-checkbox label').attr('for', `section_${ section_index }_element_${ sort_order - 1 }_required`);

                           //Edit Modal - Options, Add option, Remove option, Table
                           let count_options   = 0;
                           let count_total     = 0;

                           $modal.find('.element-modal-options').each(function(){
                               let iso = $(this).find('.checkbox-iso').val();

                               $(this).find('.checkbox-input').attr('name', `sections[${ section_index }][elements][${ sort_order - 1 }][options][${ count_options }][labels][${ iso }]`);
                               $(this).find('.checkbox-input').attr('id', `section_${ section_index }_element_${ sort_order - 1 }_option_${ count_options }_${ iso }`);
                               $(this).find('label').attr('for', `section_${ section_index }_element_${ sort_order - 1 }_option_${ count_options }_${ iso }`);
                               $(this).find('.option-label').text(count_options + 1);
                               $(this).find('.doRemoveOption').attr('data-section-index', section_index);
                               $(this).find('.doRemoveOption').attr('data-element-index', sort_order - 1);

                               count_total++;

                               if(count_total % 2 === 0) {
                                   count_options++;
                               }
                           });

                           let count_option_ids = 0;

                           $modal.find(`.checkbox-wrapper .option-id`).each(function(){
                               $(this).attr('name', `sections[${ section_index }][elements][${ sort_order - 1 }][options][${ count_option_ids }][id]`);
                               count_option_ids++;
                           });

                           $modal.find('.doAddOption').attr('data-section-index', section_index);
                           $modal.find('.doAddOption').attr('data-element-index', sort_order - 1);

                           $modal.find('.element-modal-table select').attr('id', `section_${ section_index }_element_${ sort_order - 1 }_table`);
                           $modal.find('.element-modal-table select').attr('name', `sections[${ section_index }][elements][${ sort_order - 1 }][table]`);

                           //Edit Modal - Language toggles
                           $modal.find('.edit-translation').each(function(){
                               $(this).attr('data-section-index', section_index);
                               $(this).attr('data-element-index', sort_order - 1);
                           });
                           $modal.find('.edit-translation-all').attr('data-section-index', section_index);
                           $modal.find('.edit-translation-all').attr('data-element-index', sort_order - 1);

                           //Edit Modal - Delete element
                           $modal.find('.onDeleteElement').attr('data-section-index', section_index);
                           $modal.find('.onDeleteElement').attr('data-element-index', sort_order - 1);

                           //Edit Modal - Collapse
                           $modal.find('.collapse-button').attr('data-target', `#section_${ section_index }_element_${ sort_order - 1 }_collapseColumns`);
                           $modal.find('.collapse').attr('id', `section_${ section_index }_element_${ sort_order - 1 }_collapseColumns`);
                           $modal.find('.collapse').attr('data-section-index', section_index);
                           $modal.find('.collapse').attr('data-element-index', sort_order - 1);

                           //Edit Modal - Sizes
                           $modal.find('.size-xs').attr('id', `section_${ section_index }_element_${ sort_order - 1 }_size_xs`);
                           $modal.find('.size-xs').attr('name', `sections[${ section_index }][elements][${ sort_order - 1 }][size][xs]`);
                           $modal.find('.size-sm').attr('id', `section_${ section_index }_element_${ sort_order - 1 }_size_sm`);
                           $modal.find('.size-sm').attr('name', `sections[${ section_index }][elements][${ sort_order - 1 }][size][sm]`);
                           $modal.find('.size-md').attr('id', `section_${ section_index }_element_${ sort_order - 1 }_size_md`);
                           $modal.find('.size-md').attr('name', `sections[${ section_index }][elements][${ sort_order - 1 }][size][md]`);
                           $modal.find('.size-lg').attr('id', `section_${ section_index }_element_${ sort_order - 1 }_size_lg`);
                           $modal.find('.size-lg').attr('name', `sections[${ section_index }][elements][${ sort_order - 1 }][size][lg]`);
                           $modal.find('.size-xl').attr('id', `section_${ section_index }_element_${ sort_order - 1 }_size_xl`);
                           $modal.find('.size-xl').attr('name', `sections[${ section_index }][elements][${ sort_order - 1 }][size][xl]`);

                           //Edit Modal - Slug
                           $modal.find('.element-modal-slug').find('input').attr('id', `section_${ section_index }_element_${ sort_order - 1 }_slug`);
                           $modal.find('.element-modal-slug').find('input').attr('name', `sections[${ section_index }][elements][${ sort_order - 1 }][slug]`);
                           $modal.find('.element-modal-slug').find('label').attr('for', `section_${ section_index }_element_${ sort_order - 1 }_slug`);

                           //Edit Modal - Validator
                           $modal.find('.element-modal-validator').find('input').attr('id', `section_${ section_index }_element_${ sort_order - 1 }_validator`);
                           $modal.find('.element-modal-validator').find('input').attr('name', `sections[${ section_index }][elements][${ sort_order - 1 }][validator]`);
                           $modal.find('.element-modal-validator').find('label').attr('for', `section_${ section_index }_element_${ sort_order - 1 }_validator`);

                           let random_num = Math.floor(Math.random() * 1000);

                           //Edit modal - Alignment, vertical & horizontall. Name prop is set temporarily, so the radio buttons doesn't overwrite each other.
                           $modal.find('.element-modal-alignment-vertical').find('input').attr('name', `sections[${ section_index }][elements][temp_${ sort_order - 1 }][alignment]`);
                           $modal.find('.element-modal-alignment-vertical').find('input').attr('id', `section_${ section_index }_element_${ sort_order - 1 }_aligment_vertical`);
                           $modal.find('.element-modal-alignment-vertical').find('label').attr('for', `section_${ section_index }_element_${ sort_order - 1 }_aligment_vertical`);
                           $modal.find('.element-modal-alignment-horizontal').find('input').attr('name', `sections[${ section_index }][elements][temp_${ sort_order - 1 }][alignment]`);
                           $modal.find('.element-modal-alignment-horizontal').find('input').attr('id', `section_${ section_index }_element_${ sort_order - 1 }_aligment_horizontal`);
                           $modal.find('.element-modal-alignment-horizontal').find('label').attr('for', `section_${ section_index }_element_${ sort_order - 1 }_aligment_horizontal`);

                           sort_order++;
                       });

                       //Edit modal - Alignment, vertical & horizontal. Name prop is set to the correct name.
                       $(this).children('div').each(function() {
                           if ($(this).is('#filler_div')) return;

                           let $modal = $(this).find('.element-modal-edit');

                           $modal.find('.element-modal-alignment-vertical').find('input').attr('name', `sections[${ section_index }][elements][${ sort_order_radio - 1 }][alignment]`);
                           $modal.find('.element-modal-alignment-horizontal').find('input').attr('name', `sections[${ section_index }][elements][${ sort_order_radio - 1 }][alignment]`);

                           sort_order_radio++;
                       });

                       let count = parseInt($(`#section_${ section_index }`).attr('data-element-count'));
                       $(`#section_${ section_index }`).attr('data-element-count', count + 1);
                   }
               });
           }

           //Add new section
           $('.doAddSection').on('click', function(){
               let $template   = $('#template_section').clone();
               let $modal      = $template.find('#editSectionModal_template');
               let $modal_type = $template.find('#chooseTypeModal_template');
               let count       = section_count;
               let sort_order  = $('.sortable-sections').children('div').length + 1;

               //Section
               $template.find('.sortOrderUpdateSectionVal').val(sort_order);
               $template.find('.sortOrderUpdateSectionLabel').text(sort_order);
               $template.find('.sortOrderUpdateSectionVal').attr('name', `sections[${ count }][sort_order]`);
               $template.find('.sortOrderUpdateSectionVal').attr('id', `sections_${ count }_sort_order`);
               $template.find('.section-index').val(count);
               $template.attr('id', 'section_' + count);
               $template.find('.section-modal-button').attr('data-target', `#editSectionModal_${ count }`);
               $template.find('.section-types-button').attr('data-target', `#chooseTypeModal_${ count }`);
               $template.find('.section-id').attr('name', `sections[${ count }][id]`);
               $template.removeClass('hidden');
               $template.appendTo('.sortable-sections');

               //Section edit modal
               $.ajax({
                   url: '{{ route('rl_forms.admin.forms.modals.section') }}',
                   data: {
                       section_index: count,
                   },
                   cache: false,
                   success: function(res) {
                       $template.append(res);

                       $(`#editSectionModal_${ count }`).modal('show');

                       $('.section-modal-textareas').each(function(){
                           let iso = $(this).find('input').val();

                           $R(`#section_${ count }_description_${ iso }`, {
                               lang: iso,
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
                   }
               });

               //Section type modal
               $modal_type.attr('id', `chooseTypeModal_${ count }`);
               $modal_type.find('.doChooseType').attr('data-section-index', count);
               $modal_type.find('#chooseTypeModalLabel_template').attr('id', `chooseTypeModalLabel_${ count }`);

               section_count++;

               init_drag_drop();
           });

           //On type pick
           $(document).on('click', '.doChooseType', function(){
               let type_id         = $(this).attr('data-type-id');
               let type_label      = $(this).attr('data-type-label');
               let section_index   = $(this).attr('data-section-index');
               let count           = parseInt($(`#section_${ section_index }`).attr('data-element-count'));
               let sort_order      = $(`#section_${ section_index } .sortable-elements`).children('div').length;


               $.ajax({
                   url: '{{ route('rl_forms.admin.forms.templates.element') }}',
                   data: {
                       type_id: type_id,
                       type_label: type_label,
                       section_index: section_index,
                       element_index: count,
                       sort_order: sort_order
                   },
                   cache: false,
                   success: function(res) {

                       $(`#section_${ section_index } .sortable-elements`).append(res);

                       $(`#chooseTypeModal_${ section_index }`).on('hidden.bs.modal', function () {
                           $(`#elementEditModal_section_${ section_index }_element_${ count }`).modal('show');

                           $(`#chooseTypeModal_${ section_index }`).off('hidden.bs.modal');
                       });

                       $('.element-modal-textareas').each(function(){
                           let iso = $(this).find('input').val();

                           $R(`#section_${ section_index }_element_${ count }_description_${ iso }`, {
                               lang: iso,
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

                       $(`#section_${ section_index }`).attr('data-element-count', count + 1);
                   }
               });
           });

           init_drag_drop();
       });

   </script>

   @include('rl_forms::admin.pages.forms.scripts.store')
   @include('rl_forms::admin.pages.forms.scripts.drop')

@stop


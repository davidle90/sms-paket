@extends('rl_webadmin::layouts.master')

@section('styles')
	<style>
		div.element {
			background-color:#fff;
			border:1px solid #d5e0ed;
			margin-bottom:15px;
			border-radius: 3px;
		}

        div.element .element-drag {
            background-color:#f7f7f9;
            border-bottom:1px solid #d5e0ed;
            padding:10px;
            cursor:grabbing;
            width:100%;
            display:block;
            border-top-left-radius: 2px;
            border-top-right-radius: 2px;
        }

        div.element:hover .element-drag {
            background-color:#e8f5ff;
            border-color:#10a9e6;
        }

        div.element:hover {
            border-color:#10a9e6;
        }

		div.element .form-control {
			color: #333;
			background-color:#fff;
			border:1px solid #dfe1e6;
			padding: 7px 5px 7px 5px;
		}

		div.element .element-settings {
            right: 0px;
            top: -5px;
            position: relative;
            color: #4a4a4a;
		}

        div.element .element-view {
            padding:10px;
        }

		div.element .form-control:focus {
			background-color:#fff;
			border-color: #0096db;
			outline: none;
		}

		div.element .input-group .flaticon {
			color:#9ea5b5;
		}

		div.element .input-group .form-control {
			border-right:0px;
		}

		div.element .input-group .form-control:focus {
			border:1px solid #0096db;
		}

		div.element .input-group-addon {
			padding: 0.2rem 0.55rem;
			margin-bottom: 0;
			font-size: 1.4rem;
			font-weight: normal;
			line-height: 1.25;
			color: #989ca7;
			text-align: center;
			background-color: #fff;
			border:1px solid #dfe1e6;
			border-left:0px;
			border-radius: 0rem;
		}

        div.element .element-answers .answer {
            margin-bottom:10px;
        }

		.survey-section-header {
			background-color:#319cd6;
			border-radius: 0 5px 5px;
			margin:15px 0;
			color:#fff;
			padding:10px;
		}

		.survey-section-header.first-child {
			margin-top:0;
		}

		.survey-section-header .c-button {
			padding: 0px 7px;
		}

		.survey-droparea {
			display:block;
			width:100%;
			background-color:#e8f5ff;
			border:2px dashed #319cd6;
			border-radius:4px;
			text-align:center;
			color:#43ace0;
			padding:50px;
		}
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.css" />
@endsection

@section('breadcrumbs')
    <!-- Breadcrumb -->
    <ol class="breadcrumb">
        <li class="breadcrumb-item active">Formulär</li>
        <!-- Breadcrumb Menu-->
        <li class="breadcrumb-menu d-md-down-none">
            <div class="btn-group" role="group" aria-label="Button group with nested dropdown">
                <a class="btn" href="{{ route('rl_forms.admin.forms.create') }}"><i class="icon-user"></i> Lägg till nytt formulär</a>
            </div>
        </li>
    </ol>
@endsection

@section('content')

    <div class="card">

        <!-- Card header -->
        <div class="card-header">
            <strong>Formlär</strong>
        </div>
        <div class="card-body">



            <div class="float-left">
                <h5 class="page-title">Formulär</h5>
            </div>

            <div class="float-right">


                <!-- Button group -->
                @if(isset($form) && !empty($form))
                    <span class="btn btn-link" id="doDeleteForm" data-form-id="{{ $form->id ?? '' }}">Radera</span>

                    <div class="btn-group">
                        <a class="btn btn-success" href="{{ route('rl_forms.admin.forms.create') }}">Skapa nytt formulär</a>
                        <a class="btn btn-secondary" href="{{ route('rl_forms.admin.forms.view', ['id' => $form->id]) }}" role="button">Visa formulär</a>
                    </div>
                @endif

                <!-- Post button group -->
                <span class="form-save btn btn-success">Spara</span>


            </div>

            <div class="clearfix"></div>

            <div class="row">
                <div class="col-9">

                    <form id="eventForm" method="post" action="{{ URL::route('rl_forms.admin.forms.store') }}" novalidate>

                        <div class="form-group">
                            <label for="label" class="control-label">Titel på formulär</label>
                            <input type="text" name="label" value="{{ $form->label ?? '' }}" class="form-control" />
                        </div>

                        <input type="hidden" name="form_id" value="{{ $form->id ?? '' }}" />

                        <div id="survey-sections" data-elements-count="{{ $form->elements_count or 0 }}" data-sections-count="{{ $form->sections_count or 0 }}">

                            @if(!isset($form->sections) || $form->sections->isEmpty())
                                <div class="survey-droparea">
                                    <span class="survey-droparea-text">
                                        <h3>Dynamiska formulär</h3>
                                        <p>
                                            Klicka på något av objekten i listan till höger för att lägga till det i ditt formulär.<br />
                                            Efter att du har lagt till objekt kan du flytta dem genom att "dra och släppa" dem där du vill ha dem.
                                        </p>
                                    </span>
                                </div>
                            @endif

                            @if(isset($form->sections) && !$form->sections->isEmpty())
                                <?php $y=0; ?>
                                <?php $i=0; ?>
                                @foreach($form->sections as $section)

                                    <div class="survey-section" data-unique-id="{{$y}}">

                                        <input class="section-id" type="hidden" name="section[{{$y}}][id]" value="{{$form->id}}" />
                                        <input class="section-sort-order" type="hidden" name="section[{{$y}}][sort_order]" value="{{$form->sort_order or 0}}" />

                                        {{--
                                        <div class="survey-section-header first-child" style="display:none;">

                                                <span class="float-left">
                                                    <b>Page title:</b> <span class="section-page-label section-drag">{{$form->label}}</span>
                                                </span>

                                            <div class="float-right">
                                                <b>Page:</b> <span class="section-page">{{ $y+1 }}</span> of <span class="section-page-of">{{ $form->sections_count ?? '0' }}</span>
                                                <span class="c-button survey-section-modal"><i class="text-white thin flaticon flaticon-pencil"></i></span>
                                            </div>

                                            <div class="clearfix"></div>

                                        </div>
                                        --}}

                                        <div class="@if($loop->first && $section->elements->isEmpty()) survey-droparea @endif survey-section-elements">

                                            @if($loop->first && $section->elements->isEmpty())
                                                <span class="survey-droparea-text">
                                                    <h3>Survey items</h3>
                                                    <p>Click on a survey item in the right menu to add it to this page section.<br />After adding items you can move them around by "drag and drop" on the item title.</p>
                                                </span>
                                            @endif

                                            @if(isset($section->elements) && !$section->elements->isEmpty())
                                                @foreach($section->elements as $element)
                                                    @include('rl_forms::admin.pages.forms.templates.'.$element->template->template)
                                                    <?php $i++; ?>
                                                @endforeach
                                            @endif
                                        </div>


                                        {{-- @include('app.surveys.edit.modal.section')--}}
                                    </div>
                                    <?php $y++; ?>
                                @endforeach
                            @endif

                        </div>

                    </form>

                </div>

                <div class="col-3">

                    <div class="panel" style="margin-top:87px;">
                        <div class="panel-body">

                            <a class="btn btn-block btn-primary survey-section-add" style="display: none;" href="#">Add new page</a>

                            @foreach($listElements as $e)
                                <a class="btn btn-block btn-condensed btn-left btn-secondary survey-element-add" href="" data-url="{{ route('rl_forms.admin.forms.template', ['template' => $e->template]) }}">{{ $e->label }}</a>
                            @endforeach

                        </div>
                    </div>

                </div>

            </div>












            <div id="surveySectionTemplate" style="display: none;">

                <input class="section-id" type="hidden" name="section_id" value="" />
                <input class="section-sort-order" type="hidden" name="sort_order" value="" />

                <div class="survey-section-header" style="display: none;">

                    <span class="float-left">
                        <b>Page title:</b> <span class="section-page-label section-drag">Untitled page</span>
                    </span>

                    <div class="float-right">
                        <b>Page:</b> <span class="section-page">1</span> of <span class="section-page-of">2</span>
                        <span class="c-button survey-section-modal"><i class="text-white thin flaticon flaticon-pencil"></i></span>
                    </div>

                    <div class="clearfix"></div>

                </div>

                <div class="survey-section-elements"></div>


                <!-- Modal -->
                <div class="modal fade" id="surveySectionModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLongTitle">
                                    Page settings
                                    <button type="button" class="close pull-right" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true"><i class="essential essential-multiply"></i></span>
                                    </button>
                                </h5>

                            </div>
                            <div class="modal-body">

                                <div class="form-group">
                                    <label class="control-label">Page name</label>
                                    <input type="text" name="replace_label" class="form-control" value="Untitled page" />
                                </div>

                                <div class="form-group">
                                    <label class="control-label">Page description</label>
                                    <textarea class="form-control" name="replace_description"></textarea>
                                </div>


                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-danger float-left section-delete" data-dismiss="modal">Delete</button>
                                <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

@endsection

@section('scripts')

	{{-- <script src="{{ elixir('js/jquery-ui.min.js') }}"></script>--}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.js"></script>

	<script type="text/javascript">

        function recalculatePages()
        {
            var NumOfPages = $('.survey-section').length;

            $('.survey-section').each(function(index, test){
                $(this).find('.section-page').html(index+1);
                $(this).find('.section-page-of').html(NumOfPages);
                $(this).find('.section-sort-order').val(index);
            });

        }

        function rebindElementSettings()
        {
            $('.element-settings').off('click').on('click', function(e){
                e.preventDefault();
                var uniqueId = parseInt($(this).closest('.element').attr('data-unique-id'));
                $('#ElementSettingsModal_'+uniqueId).modal('show');
            });
        }

        function sortAllElementOptions()
        {

        }

        function rebindDeleteOptions()
        {
            $('.element-answers-delete-option').off('click').on('click', function(e){
                e.preventDefault();
                $(this).closest('.answer').remove();
                resortOptions();
            });
        }

        function rebindAddOptions()
        {
            $('.element-answers-add-option').off('click').on('click', function(e){
                e.preventDefault();

                var $template = $(this).closest('.element').find('.template-answer').clone();

                var elementCount = parseInt($(this).closest('.element').attr('data-unique-id'));
                var sectionUniqueId = parseInt($(this).closest('.survey-section').attr('data-unique-id'));

                var currentCount = parseInt($(this).closest('.element').find('.element-answers').attr('data-answers-count'));
                var sort_order = $(this).closest('.element').find('.element-answers > .answers > .answer').length;
                var expectedCount = currentCount+1;
                $(this).closest('.element').find('.element-answers').attr('data-answers-count',expectedCount);
                console.log(expectedCount);

                $template.removeClass('template-answer');
                $template.find('[name="replace_id"]').attr('name', 'section['+sectionUniqueId+'][element]['+elementCount+'][options]['+expectedCount+'][id]');
                $template.find('[name="replace_other"]').attr('name', 'section['+sectionUniqueId+'][element]['+elementCount+'][options]['+expectedCount+'][other]');
                $template.find('[name="replace_sort_order"]').attr('name', 'section['+sectionUniqueId+'][element]['+elementCount+'][options]['+expectedCount+'][sort_order]').val(sort_order);
                $template.find('[name="replace_label"]').attr('name', 'section['+sectionUniqueId+'][element]['+elementCount+'][options]['+expectedCount+'][label]');
                $template.find('.answer-number').html(sort_order+1);

                $(this).closest('.element').find('.element-answers > .answers').append($template);

                rebindDeleteOptions();
                sortableOptions();
                resortOptions();
            });
        }

        function rebindToggleOtherOptions()
        {

            $('.element-option-other-toggle').off('change').on('change', function(e){
                e.preventDefault();
                if($(this).is(':checked')){
                    var $template = $(this).closest('.element').find('.template-answer').clone();
                    var elementCount = parseInt($(this).closest('.element').attr('data-unique-id'));
                    var currentCount = parseInt($(this).closest('.element').find('.element-answers').attr('data-answers-count'));
                    var sort_order = $(this).closest('.element').find('.element-answers > .answer').length;
                    var expectedCount = currentCount+1;
                    $(this).closest('.element').find('.element-answers').attr('data-answers-count',expectedCount);

                    $template.removeClass('template-answer');
                    $template.find('[name="replace_id"]').attr('name', 'element['+elementCount+'][options]['+expectedCount+'][id]');
                    $template.find('[name="replace_other"]').attr('name', 'element['+elementCount+'][options]['+expectedCount+'][other]').val(1);
                    $template.find('[name="replace_sort_order"]').attr('name', 'element['+elementCount+'][options]['+expectedCount+'][sort_order]').val(999);
                    $template.find('[name="replace_label"]').attr('name', 'element['+elementCount+'][options]['+expectedCount+'][label]');
                    $template.find('.control-label').html('Other');
                    $(this).closest('.element').find('.element-answers > .other').append($template);
                } else {
                    $(this).closest('.element').find('.element-answers > .other').html('');
                }
            });
        }

        function rebindDeleteElementButtons()
        {
            $('.element-delete').off('click').on('click', function(e){
                $(this).closest('.modal').on('hidden.bs.modal', function (e) {
                    $(this).closest('.element').remove();
                    resortElements();

                    if($('.element').length == 0){
                        $('.survey-droparea').show();
                    }

                });
            });
        }


        function rebindSectionSettings()
        {
            $('.survey-section-modal').off('click').on('click', function(e){
                e.preventDefault();
                var sectionUniqueId = parseInt($(this).closest('.survey-section').attr('data-unique-id'));
                $('#surveySectionModal_'+sectionUniqueId).modal('show');
            });
        }

        function rebindDeleteSectionButtons()
        {
            $('.section-delete').off('click').on('click', function(e){
                $(this).closest('.modal').on('hidden.bs.modal', function (e) {

                    if($('.survey-section').length > 1){
                        $(this).closest('.survey-section').remove();
                    }
                    recalculatePages();
                    resortElements();
                });
            });
        }

        function resortElements()
        {
            $('.element').each(function(index, element){
                $(this).find('.element-number').html(index+1);
                $(this).find('.element-sort-order').val(index);
            });
        }

        function resortOptions()
        {
            $('.element').each(function(){
                $(this).find('.answer').each(function(index, element){
                    $(this).find('.element-option-number').html(index+1);
                    $(this).find('.element-option-sort-order').val(index);
                });
            });
        }

        function sortableElements()
        {
            $('.survey-section-elements').sortable({

                connectWith: '.survey-section-elements',
                handle: '.element-drag',
                cursor: 'move',
                placeholder: 'placeholder',
                forcePlaceholderSize: false,
                opacity: 0.4,
                tolerance: 'pointer',
                stop: function(event, ui)
                {

                    var $el = $(ui.item);

                    var sectionUniqueId = parseInt($el.closest('.survey-section').attr('data-unique-id'));
                    var elementUniqueId = parseInt($el.attr('data-unique-id'));

                    $el.find('.element-id').attr('name','section['+sectionUniqueId+'][element]['+elementUniqueId+'][id]');
                    $el.find('.element-list-element-id').attr('name','section['+sectionUniqueId+'][element]['+elementUniqueId+'][list_element_id]');
                    $el.find('.element-sort-order').attr('name','section['+sectionUniqueId+'][element]['+elementUniqueId+'][sort_order]');
                    $el.find('.element-label').attr('name','section['+sectionUniqueId+'][element]['+elementUniqueId+'][label]');
                    $el.find('.element-help-text').attr('name','section['+sectionUniqueId+'][element]['+elementUniqueId+'][help_text]');

                    $el.find('.element-settings-options-required').attr('name','section['+sectionUniqueId+'][element]['+elementUniqueId+'][required]');
                    $el.find('.element-settings-options-multiple').attr('name','section['+sectionUniqueId+'][element]['+elementUniqueId+'][multiple]');
                    $el.find('.element-settings-options-other').attr('name','section['+sectionUniqueId+'][element]['+elementUniqueId+'][other]');

                    $el.find('.answer').not('.template-answer').each(function(index, element){
                        console.log(index);
                        console.log(element);
                        $(this).find('.element-option-id').attr('name','section['+sectionUniqueId+'][element]['+elementUniqueId+'][options]['+index+'][id]');
                        $(this).find('.element-option-other').attr('name','section['+sectionUniqueId+'][element]['+elementUniqueId+'][options]['+index+'][other]');
                        $(this).find('.element-option-sort-order').attr('name','section['+sectionUniqueId+'][element]['+elementUniqueId+'][options]['+index+'][sort_order]');
                        $(this).find('.element-option-label').attr('name','section['+sectionUniqueId+'][element]['+elementUniqueId+'][options]['+index+'][label]');
                    });

                    resortElements();
                },
                start: function (e, ui) {
                    ui.placeholder.height(ui.item.height());
                    ui.placeholder.html('Placera fråga här').css('padding', ui.item.height()/2-12+'px 0px');
                }
            });
        }

        function sortableOptions()
        {
            $('.answers').sortable({
                connectWith: '.answers',
                handle: 'label',
                cursor: 'move',
                placeholder: 'placeholder',
                forcePlaceholderSize: false,
                opacity: 0.4,
                tolerance: 'pointer',
                stop: function(event, ui)
                {
                    var $el = $(ui.item);

                    var sectionUniqueId = parseInt($el.closest('.survey-section').attr('data-unique-id'));
                    var elementUniqueId = parseInt($el.closest('.element').attr('data-unique-id'));

                    $el.closest('.element').find('.answer').not('.template-answer').each(function(index, element) {
                        $(this).find('.element-option-id').attr('name', 'section[' + sectionUniqueId + '][element][' + elementUniqueId + '][options][' + index + '][id]');
                        $(this).find('.element-option-other').attr('name', 'section[' + sectionUniqueId + '][element][' + elementUniqueId + '][options][' + index + '][other]');
                        $(this).find('.element-option-sort-order').attr('name', 'section[' + sectionUniqueId + '][element][' + elementUniqueId + '][options][' + index + '][sort_order]');
                        $(this).find('.element-option-label').attr('name', 'section[' + sectionUniqueId + '][element][' + elementUniqueId + '][options][' + index + '][label]');
                    });
                    resortOptions();

                },
                start: function (e, ui) {
                    ui.placeholder.height(ui.item.height());
                    ui.placeholder.html('Placera svar här').css('padding', ui.item.height()/2-12+'px 0px');
                }
            });
        }


		function updateFormObjects($that){
			element     = $that.attr('data-element');
			id          = $that.attr('data-id');
			title       = $that.find('input[id^="input_title_"]').val();
			helptext    = $that.find('textarea[id^="input_helptext_"]').val();

			if(!title){
				title = '<i class="text-danger">Titel på din fråga</i>';
			}
			if(!helptext){
				helptext = '<i class="text-danger">Hjälp text som förklarar vad man förväntas att välja</i>';
			}

			$('#element_' + id + '_title').html(title);
			$('#element_' + id + '_helptext').html(helptext);

			console.log($('#element_' + id + '_helptext'));

			if($that.find('input[id^="input_required_"]').is(':checked')){
				$('#element_' + id + '_required').hide();
			} else {
				$('#element_' + id + '_required').show();
			}

			if(element == 'dropdown'){
				answers = $that.find('.element-option-label').serializeArray();
				$('#element_' + id + '_select').empty().append('<option value=""></option>');
				$.each( answers, function( key, value ) {
					$('#element_' + id + '_select').append('<option value="' + key + '">' + value.value + '</option>');
				});
			}

			if(element == 'multiple'){
				if($that.find('input[id^="input_checkbox_"]').is(':checked')){
					multiple_type = 'checkbox';
				} else {
					multiple_type = 'radio';
				}
				answers = $that.find('.element-option-label').serializeArray();
				$('#element_' + id + '_multiple').empty();
				$.each( answers, function( key, value ) {
					$('#element_' + id + '_multiple').append('<div class="' + multiple_type + '"><label><input type="' + multiple_type + '" name="optradio" disabled> ' + value.value + '</label></div>');
				});
			}
		}


        $(document).ready(function(){

            /*
             * On modal hide
             */
            $('.form-element-modal').on('hide.bs.modal', function (e) {
				updateFormObjects($(this));
            });

            rebindDeleteOptions();
            rebindAddOptions();
            rebindToggleOtherOptions();
            rebindElementSettings();
            rebindDeleteElementButtons();
            rebindSectionSettings();
            rebindDeleteSectionButtons();
            sortableElements();
            sortableOptions();

            $('.survey-section-add').on('click', function(e){
                e.preventDefault();

                var $sectionsContainer = $('#survey-sections');

                var currentCount = parseInt($sectionsContainer.attr('data-sections-count'));
                expectedCount = currentCount+1;
                $sectionsContainer.attr('data-sections-count',expectedCount);

                $template = $('#surveySectionTemplate')
                    .clone()
                    .removeAttr('id')
                    .addClass('survey-section')
                    .attr('data-unique-id', expectedCount)
                    .css('display','');

                $template.find('input[name=section_id]').attr('name','section['+expectedCount+'][id]');
                $template.find('input[name=sort_order]').attr('name','section['+expectedCount+'][sort_order]');

                $template.find('#surveySectionModal').attr('id', 'surveySectionModal_'+expectedCount);
                $template.find('input[name=replace_label]').attr('name', 'section['+expectedCount+'][label]');
                $template.find('textarea[name=replace_description]').attr('name', 'section['+expectedCount+'][description]');

                $sectionsContainer.append($template);

                recalculatePages();
                rebindSectionSettings();
                rebindDeleteSectionButtons();
                sortableElements();

            });



			/*
			 * Add new element to last page
			 */
            $('.survey-element-add').on('click', function(e){
                e.preventDefault();

                if(parseInt($('#survey-sections').attr('data-sections-count')) == 0){
                    $('.survey-section-add').trigger('click');
                }

                if($('.survey-section').length > 0){
                    $('.survey-droparea').hide();
                }

                var url = $(this).attr('data-url');
                var $surveyContainer = $('#survey-sections');
                var currentCount = parseInt($surveyContainer.attr('data-elements-count'));
                expectedCount = currentCount+1;
                $surveyContainer.attr('data-elements-count',expectedCount);

                var sectionId = $('.survey-section:last').closest('.survey-section').attr('data-unique-id');

                $.get(url+'?count='+expectedCount+'&sections='+sectionId, function(data){

                    $('.survey-section:last').find('.survey-droparea').removeClass('survey-droparea').html('');
                    $('.survey-section:last').find('.survey-section-elements').append(data);

                    rebindDeleteOptions();
                    rebindAddOptions();
                    rebindToggleOtherOptions();
                    rebindElementSettings();
                    rebindDeleteElementButtons();
                    sortableElements();
                    resortElements();
                    resortOptions();

					$('.form-element-modal').off('hide.bs.modal').on('hide.bs.modal', function (e) {
						updateFormObjects($(this));
					});

				});
            });


			/*
			 * Save survey
			 */
            $('.form-save').on('click', function(e){
                e.preventDefault();

                var $form = $('#eventForm');

                $.ajax({
                    type: "POST",
                    url: $form.attr('action'),
                    cache: false,
                    dataType: 'json',
                    data: $form.serialize(),
                    beforeSend: function(){},
                    success: function (data) {

                        if(data.status == 1) {

							swal({
								title: data.message.title,
								text: data.message.text,
								type: "success",
								timer: 6000,
								confirmButtonText: "OK"
							},function(){
								if(data.redirect){
									window.location = data.redirect;
								}
							});

                        } else if(data.status == 0) {

                            swal({
                                title: data.message.title,
                                text: data.message.text,
                                type: "error",
                                timer: 6000,
                                confirmButtonText: "OK"
                            });

                            /** Collect errors from response **/
                            errors = data.errors;

                            /** Mark form fields with errors warnings **/
                            $.each(errors, function(id, message) {
                                $("input[name="+id+"], select[name="+id+"], textarea[name="+id+"]").parent().addClass('has-danger');
                                $("input[name="+id+"], select[name="+id+"], textarea[name="+id+"]").after('<div class="tooltip-static tooltip-error"><div class="tooltip tooltip-bottom" role="tooltip"><div class="tooltip-inner">' + message + "</div></div></div>");
                            });

                        }

                    },
                    error: function(xhr, textStatus, errorThrown){

                        swal({
                            title: 'Error!',
                            text: 'An unexpected error has occurred.',
                            type: "error",
                            timer: 6000,
                            confirmButtonText: "OK"
                        });

                    }
                });


            });


			/*
			 * Delete survey
			 */
            $('.survey-delete').on('click', function(e){
                e.preventDefault();

                var url = $(this).attr('data-url');
                var surveyId = $(this).attr('data-survey-id');

                swal({
                        title: "Delete survey data!",
                        text: "All data of this survey. including statistics and participant data will be deleted.\n\n Type DELETE to delete the participant.",
                        type: "input",
                        inputType: "text",
                        showCancelButton: true,
                        closeOnConfirm: false,
                        showLoaderOnConfirm: true,
                        animation: "slide-from-top",
                        inputPlaceholder: "Type DELETE"
                    },
                    function(inputValue){

                        if (inputValue === false) {
                            return false;
                        }

                        if (inputValue === "") {
                            swal.showInputError("You need to type the word: DELETE");
                            return false
                        }

                        if(inputValue.toLowerCase() != 'delete'){
                            swal.showInputError("You need to type the word: DELETE");
                            return false
                        }

                        $.ajax({
                            type: "POST",
                            url: url,
                            cache: false,
                            dataType: 'json',
                            data: {
                                survey_id: surveyId
                            },
                            beforeSend: function(){},
                            success: function (data) {

                                if(data.status == 1) {

                                    swal({
                                            title: data.message.title,
                                            text: data.message.text,
                                            type: "success",
                                            timer: 6000,
                                            confirmButtonText: "OK"
                                        },
                                        function(){
											{{-- window.location = '{{ URL::route('survey-index') }}';--}}
                                        });

                                } else if(data.status == 0) {

                                    swal({
                                        title: data.message.title,
                                        text: data.message.text,
                                        type: "error",
                                        timer: 6000,
                                        confirmButtonText: "OK"
                                    });

                                }

                            },
                            error: function(xhr, textStatus, errorThrown){

                                swal({
                                    title: 'Error!',
                                    text: 'An unexpected error has occurred.',
                                    type: "error",
                                    timer: 6000,
                                    confirmButtonText: "OK"
                                });

                            }
                        });


                    });



            });



			/*
			 * Open survey settings modal
			 */
            $('.survey-settings').on('click', function(e){
                e.preventDefault();
                $('#surveySettingsModal').modal('show');
            });

			/*
			 * Save survey settings from modal
			 */
            $('.survey-settings-save').on('click', function(e){
                e.preventDefault();

                $form = $('#surveySettingsForm');

                $.ajax({
                    type: "POST",
                    url: $form.attr('action'),
                    cache: false,
                    dataType: 'json',
                    data: $form.serialize(),
                    beforeSend: function(){},
                    success: function (data) {

                        $('#surveySettingsModal').modal('hide');

                        if(data.status == 1) {


                            $('.survey-label').html(data.survey.label);



                            swal({
                                title: data.message.title,
                                text: data.message.text,
                                type: "success",
                                timer: 6000,
                                confirmButtonText: "OK"
                            });

                        } else if(data.status == 0) {

                            swal({
                                title: data.message.title,
                                text: data.message.text,
                                type: "error",
                                timer: 6000,
                                confirmButtonText: "OK"
                            });

                        }

                    },
                    error: function(xhr, textStatus, errorThrown){
                        swal({
                            title: 'Error!',
                            text: 'An unexpected error has occurred.',
                            type: "error",
                            timer: 6000,
                            confirmButtonText: "OK"
                        });
                    }
                });


            });


			/*
			 * Publish/Unpublish survey
			 */
            $('.survey-publish').on('click', function(e){
                e.preventDefault();
                console.log('click');

                var $button = $(this);
                var url = $(this).attr('data-url');
                var published = parseInt($(this).attr('data-published'));
                var surveyId = $(this).attr('data-survey-id');

                var newPublish = 0;
                if(published == 0){
                    newPublish = 1;
                }

                $.ajax({
                    type: "POST",
                    url: url,
                    cache: false,
                    dataType: 'json',
                    data: {
                        survey_id: surveyId,
                        published: newPublish
                    },
                    beforeSend: function(){},
                    success: function (data) {

                        if(data.status == 1) {


                            if(newPublish == 1){
                                $button.html('Unpublish');
                            } else {
                                $button.html('Publish');
                            }

                            $button.attr('data-published',newPublish);

                            swal({
                                title: data.message.title,
                                text: data.message.text,
                                allowEscapeKey: false,
                                type: "success",
                                timer: 6000,
                                confirmButtonText: "OK"
                            },function(){
                                location.reload();
                            });

                        } else if(data.status == 0) {

                            swal({
                                title: data.message.title,
                                text: data.message.text,
                                type: "error",
                                timer: 6000,
                                confirmButtonText: "OK"
                            });

                        }

                    },
                    error: function(xhr, textStatus, errorThrown){
                        swal({
                            title: 'Error!',
                            text: 'An unexpected error has occurred.',
                            type: "error",
                            timer: 6000,
                            confirmButtonText: "OK"
                        });
                    }
                });


            });




			$('#doDeleteForm').on('click', function(){

				form_id = $(this).attr('data-form-id');





				swal({
						title: "Radera formulär!",
						text: "All data tillhörande formuläret kommer raderas, det går inte att återställa. Vänligen skriv RADERA för att ta bort.",
						type: "input",
						inputType: "text",
						showCancelButton: true,
						closeOnConfirm: false,
						showLoaderOnConfirm: true,
						animation: "slide-from-top",
						inputPlaceholder: "Skriv RADERA"
					},
					function(inputValue){

						if (inputValue === false) {
							return false;
						}

						if (inputValue === "") {
							swal.showInputError("Du behöver skriva ordet: RADERA");
							return false
						}

						if(inputValue.toLowerCase() != 'radera'){
							swal.showInputError("Du behöver skriva ordet: RADERA");
							return false
						}

						$.ajax({
							type: "POST",
							url: "{{ URL::route('rl_forms.admin.forms.destroy') }}",
							cache: false,
							dataType: 'json',
							data: {
								form_id: form_id,
							},
							beforeSend: function(){},
							success: function (data) {

								/** Print response to screen **/
								//alert(JSON.stringify(data));

								if(data.status == 1) {

									swal({
										title: 'Klart!',
										text: 'Formuläret har blivit raderat.',
										type: "success",
										timer: 6000,
										confirmButtonText: "OK"
									},function(){
										if(data.redirect){
											window.location = data.redirect;
										}
									});

								} else if(data.status == 0) {

									swal({
										title: data.message.title,
										text: data.message.text,
										type: "error",
										timer: 6000,
										confirmButtonText: "OK"
									});

								}

							},
							error: function(xhr, textStatus, errorThrown){

								/** Something went terribly wrong! Print json response to screen **/
								swal({
									title: 'Error!',
									text: 'An unexpected error has occurred, if errors persists, contact support!',
									type: "error",
									timer: 6000,
									confirmButtonText: "OK"
								});

							}
						});


					});


			});


        });

	</script>
@endsection

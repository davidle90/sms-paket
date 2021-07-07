<!-- Modal -->
@if(isset($template) && $template === true)
    <div class="modal fade element-modal-edit" id="elementEditModal_section_{{ $section_index }}_element_{{ $element_index }}" tabindex="-1" role="dialog" aria-labelledby="editSectionModalLabel" aria-hidden="true" data-keyboard="false" data-backdrop="static">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header bg-dark">
                    <h5 class="modal-title text-white bold" id="elementEditModalLabel_section_{{ $section_index }}_element_{{ $element_index }}">Redigera fråga - {{ $type_label }}</h5>
                    <span style="margin-top:0.15rem;" aria-label="Close" class="onCloseModal">
                        <i class="essential-sm essential-multiply pointer thin text-white"></i>
                    </span>
                </div>

                <div id="elementEditModal_section_{{ $section_index }}_element_{{ $element_index }}_body" class="modal-body pb-4 pt-4" style="max-height: calc(100vh - 185px); overflow-y: auto">
                    @switch($type_id)
                        @case(1)
                            @include('rl_forms::admin.pages.forms.modals.elements.input')
                            @break
                        @case(2)
                            @include('rl_forms::admin.pages.forms.modals.elements.select')
                            @break
                        @case(3)
                            @include('rl_forms::admin.pages.forms.modals.elements.multiselect')
                            @break
                        @case(4)
                            @include('rl_forms::admin.pages.forms.modals.elements.checkbox')
                            @break
                        @case(5)
                            @include('rl_forms::admin.pages.forms.modals.elements.radio')
                            @break
                        @case(6)
                            @include('rl_forms::admin.pages.forms.modals.elements.textarea')
                            @break
                    @endswitch

                </div>

                <div class="modal-footer">
                    <button
                            type="button"
                            class="btn btn-outline-danger active mr-auto onDeleteElement"
                            data-section-index="{{ $section_index }}"
                            data-element-index="{{ $element_index }}"
                            data-dismiss="modal"
                    >Ta bort</button>
                    <div>
                        <span
                            class="btn btn-link edit-translation-all"
                            data-mode="show"
                            data-section-index="{{ $section_index }}"
                            data-element-index="{{ $element_index }}"
                        >Redigera språk</span>
                        <button
                            type="button"
                            class="btn btn-outline-success active doUpdateElement"
                            data-section-index="{{ $section_index }}"
                            data-element-index="{{ $element_index }}"
                            data-type-id="{{ $type_id }}"
                        >Uppdatera fråga</button>
                    </div>
                </div>

            </div>
        </div>
    </div>
@else
    <div class="modal fade element-modal-edit" id="elementEditModal_section_{{ $section_index }}_element_{{ $element_index }}" tabindex="-1" role="dialog" aria-labelledby="editSectionModalLabel" aria-hidden="true" data-keyboard="false" data-backdrop="static">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header bg-dark">
                    <h5 class="modal-title text-white bold" id="elementEditModalLabel_section_{{ $section_index }}_element_{{ $element_index }}">Redigera fråga - {{ $element->type->label }}</h5>
                    <span style="margin-top:0.15rem;" aria-label="Close" class="onCloseModal">
                        <i class="essential-sm essential-multiply pointer thin text-white"></i>
                </span>
                </div>
                <div id="elementEditModal_section_{{ $section_index }}_element_{{ $element_index }}_body" class="modal-body pb-4 pt-4" style="max-height: calc(100vh - 185px); overflow-y: auto">
                    @switch($element->type->id)
                        @case(1)
                            @include('rl_forms::admin.pages.forms.modals.elements.input')
                            @break
                        @case(2)
                            @include('rl_forms::admin.pages.forms.modals.elements.select')
                            @break
                        @case(3)
                            @include('rl_forms::admin.pages.forms.modals.elements.multiselect')
                            @break
                        @case(4)
                            @include('rl_forms::admin.pages.forms.modals.elements.checkbox')
                            @break
                        @case(5)
                            @include('rl_forms::admin.pages.forms.modals.elements.radio')
                            @break
                        @case(6)
                            @include('rl_forms::admin.pages.forms.modals.elements.textarea')
                            @break
                    @endswitch
                </div>

                <div class="modal-footer">
                    <button
                            type="button"
                            class="btn btn-outline-danger active mr-auto onDeleteElement"
                            data-section-index="{{ $section_index }}"
                            data-element-index="{{ $element_index }}"
                            data-dismiss="modal"
                    >Ta bort</button>
                    <div>
                        <span
                                class="btn btn-link edit-translation-all"
                                data-mode="show"
                                data-section-index="{{ $section_index }}"
                                data-element-index="{{ $element_index }}"
                        >Redigera språk</span>
                        <button
                                type="button"
                                class="btn btn-outline-success active doUpdateElement"
                                data-section-index="{{ $section_index }}"
                                data-element-index="{{ $element_index }}"
                                data-type-id="{{ $element->type->id }}"
                        >Uppdatera fråga</button>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endif

@if(!isset($template) || $template === false)
    @push('scripts')
@endif
    <script type="text/javascript">
        $(document).ready(function(){
            let $modal = $('#elementEditModal_section_{{ $section_index }}_element_{{ $element_index }}');

            let slug_remove_error = function() {
                $modal.find('.element-slug').removeClass('is-invalid');
                $modal.find('.error-block').remove();

                $modal.find('.element-slug').off('input', slug_remove_error);
            }

            let slug_validator = function() {
                toastr.error('Some form value is missing or not properly filled out, please check your input and try again', 'Form validation error!', toastr.options = {
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

                /** Mark form fields with errors warnings **/
                $modal.find('.element-slug').removeClass('is-invalid');
                $modal.find('.error-block').remove();
                $modal.find('.element-slug').addClass('is-invalid');
                $modal.find('.element-slug').parent().after("<div class='error-block'>" + 'The slug field is required.' + "</div>");

                $modal.find('.element-slug-label')[0].scrollIntoView({
                    behavior: "smooth"
                });

                $modal.find('.element-slug').on('input', slug_remove_error);
            }

            $('.select-size').select2({
                placeholder: "Ej vald",
                allowClear: true,
                minimumResultsForSearch: -1
            });

            $(".collapse").off('show.bs.collapse').on('show.bs.collapse', function(){
                let section_index = $(this).attr('data-section-index');
                let element_index = $(this).attr('data-element-index');

                $(this).parent().find('.collapse-icon').removeClass("icon-arrow-down").addClass("icon-arrow-up");

                $(this).off('shown.bs.collapse').on('shown.bs.collapse', function(){
                    $(`#elementEditModal_section_${ section_index }_element_${ element_index }_body`).scrollTop($(`#elementEditModal_section_${ section_index }_element_${ element_index }_body`)[0].scrollHeight);
                });
            }).off('hide.bs.collapse').on('hide.bs.collapse', function(){
                $(this).parent().find('.collapse-icon').removeClass("icon-arrow-up").addClass("icon-arrow-down");
            });

            $('#elementEditModal_section_{{ $section_index }}_element_{{ $element_index }}').on('hidden.bs.modal', function(){
                $(this).find('.translation').each(function(){
                    $(this).hide();
                });

                $(this).find('.edit-translation-all').attr('data-mode', 'show');
                $(this).find('.edit-translation-all').text('Redigera språk');

                $(this).find('.edit-translation').each(function(){
                    $(this).attr('data-mode', 'show');
                    $(this).text('Redigera språk');
                });

                $(this).find('.collapse-icon').removeClass("icon-arrow-up").addClass("icon-arrow-down");
                $(this).find('.collapse').collapse('hide');
            });

            $('.edit-translation-all').off('click').on('click', function(){
                let mode            = $(this).attr('data-mode');
                let section_index   = $(this).attr('data-section-index');
                let element_index   = $(this).attr('data-element-index');
                let wrappers        = [
                    'label',
                    'description',
                    'checkbox',
                    'required'
                ];

                for(let slug of wrappers) {
                    $(`#elementEditModal_section_${ section_index }_element_${ element_index } .${ slug }-wrapper`).find('.translation').each(function(){
                        if(mode === 'show') {
                            $(this).show()
                        } else {
                            $(this).hide();
                        }
                    });
                }

                if(mode === 'show') {
                    $(this).attr('data-mode', 'hide');
                    $(this).text('Dölj språk');
                } else {
                    $(this).attr('data-mode', 'show');
                    $(this).text('Redigera språk');
                }

                $(`#elementEditModal_section_${ section_index }_element_${ element_index } .edit-translation`).each(function(){
                    if(mode === 'show') {
                        $(this).attr('data-mode', 'hide');
                        $(this).text('Dölj språk');
                    } else {
                        $(this).attr('data-mode', 'show');
                        $(this).text('Redigera språk');
                    }
                });
            });

            $modal.on('click', '.doUpdateElement', function(){
                let section_index   = $(this).attr('data-section-index');
                let element_index   = $(this).attr('data-element-index');
                let type_id         = $(this).attr('data-type-id');

                let label           = $(`#section_${ section_index }_element_${ element_index }_label_sv`).val();
                let slug            = $(`#section_${ section_index }_element_${ element_index }_slug`).val() ?? '';
                let description     = $(`#section_${ section_index }_element_${ element_index }_description_sv`).val();
                let required_text   = $(`#section_${ section_index }_element_${ element_index }_required_text_sv`).val();
                let required        = $(`#section_${ section_index }_element_${ element_index }_required`).is(':checked');
                let table_id        = $(`#section_${ section_index }_element_${ element_index }_table`).val();
                let alignment       = $modal.find('.element-modal-alignment input:checked').val() ?? null;
                let options         = [];

                $modal.find('.checkbox-wrapper').children('div').each(function(){
                    options.push($(this).find('.checkbox-in-sv').val());
                });

                if(!slug || slug == '') {
                    slug_validator();
                    return;
                }

                $.ajax({
                    url: '{{ route('rl_forms.admin.forms.templates.card') }}',
                    data: {
                        section_index: section_index,
                        element_index: element_index,
                        type_id: type_id,
                        label: label,
                        description: description,
                        required_text: required_text,
                        required: required,
                        table_id: table_id,
                        alignment: alignment,
                        options: options
                    },
                    cache: false,
                    success: function(res) {
                        $(`#section_${ section_index }_element_${ element_index }`).find('.update-card-body').html(res);

                        $R(`#section_${ section_index }_element_${ element_index } .redactor-card`, {
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

                        $modal.modal('hide');
                    }
                });
            });

            $('.onDeleteElement').off('click').on('click', function(){
                let section_index = $(this).attr('data-section-index');
                let element_index = $(this).attr('data-element-index');

                let temp_event = function () {

                    $(`#deleteElementModal`).find('.doDeleteElement').attr('data-section-index', section_index);
                    $(`#deleteElementModal`).find('.doDeleteElement').attr('data-element-index', element_index);
                    $(`#deleteElementModal`).modal('show');

                    $(`#elementEditModal_section_${ section_index }_element_${ element_index }`).off('hidden.bs.modal', temp_event);
                }

                $(`#elementEditModal_section_${ section_index }_element_${ element_index }`).on('hidden.bs.modal', temp_event);
            });

            $('.onCloseModal').on('click', function(){
                let slug = $modal.find('.element-slug').val();

                if(!slug || slug == '') {
                    slug_validator();
                    return;
                }

                $modal.modal('hide');
            });

        });
    </script>
@if(!isset($template) || $template === false)
    @endpush
@endif


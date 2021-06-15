<!-- Modal -->
@if(isset($template) && $template === true)
    <div class="modal fade" id="elementEditModal_section_{{ $section_index }}_element_create" tabindex="-1" role="dialog" aria-labelledby="editSectionModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header bg-dark">
                    <h5 class="modal-title text-white bold" id="elementEditModalLabel_section_{{ $section_index }}_element_create">Skapa fråga - {{ $type_label }}</h5>
                    <span style="margin-top:0.15rem;" data-dismiss="modal" aria-label="Close">
                        <i class="essential-sm essential-multiply pointer thin text-white"></i>
                </span>
                </div>
                <div id="elementEditModal_section_{{ $section_index }}_element_create_body" class="modal-body pb-4 pt-4" style="max-height: calc(100vh - 185px); overflow-y: auto">

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
                    <button type="button" class="btn btn-link mr-auto" data-dismiss="modal">Stäng</button>
                    <div>
                        <span class="btn btn-link edit-translation-all" data-mode="show">Redigera språk</span>
                        <button type="button" class="btn btn-outline-success active doCreateElement" data-section-index="{{ $section_index }}" data-dismiss="modal">Skapa fråga</button>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <script type="text/javascript">
        $(document).ready(function(){

            $('.edit-translation-all').on('click', function(){
                let mode = $(this).attr('data-mode');
                let wrappers = [
                    'label',
                    'description',
                    'checkbox',
                    'required'
                ];

                for(let slug of wrappers) {
                    $(`.${ slug }-wrapper`).find('.translation').each(function(){
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

                $('.edit-translation').each(function(){
                    if(mode === 'show') {
                        $(this).attr('data-mode', 'hide');
                        $(this).text('Dölj språk');
                    } else {
                        $(this).attr('data-mode', 'show');
                        $(this).text('Redigera språk');
                    }
                });
            });
        });
    </script>
@else
    <div class="modal fade element-modal-edit" id="elementEditModal_section_{{ $section_index }}_element_{{ $element_index }}" tabindex="-1" role="dialog" aria-labelledby="editSectionModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header bg-dark">
                    <h5 class="modal-title text-white bold" id="elementEditModalLabel_section_{{ $section_index }}_element_{{ $element_index }}">Redigera fråga - {{ $element->type->label }}</h5>
                    <span style="margin-top:0.15rem;" data-dismiss="modal" aria-label="Close">
                        <i class="essential-sm essential-multiply pointer thin text-white"></i>
                </span>
                </div>
                <div class="modal-body pb-4 pt-4">
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

            </div>
        </div>
    </div>
@endif


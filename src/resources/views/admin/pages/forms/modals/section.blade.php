<!-- Modal -->
<div class="modal fade" id="editSectionModal_{{ $section_index }}" tabindex="-1" role="dialog" aria-labelledby="editSectionModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-dark">
                <h5 class="modal-title text-white bold" id="editSectionModalLabel_{{ $section_index }}">Redigera sektion</h5>
                <span style="margin-top:0.15rem;" data-dismiss="modal" aria-label="Close">
                        <i class="essential-sm essential-multiply pointer thin text-white"></i>
                    </span>
            </div>
            <div class="modal-body">

                <h6>
                    <span class="bold">Label</span>
                    <span
                            class="text-link float-right edit-translation-section text-normal"
                            data-target="label"
                            data-mode="show"
                            data-section-index="{{ $section_index }}"
                    >
                        Redigera språk
                    </span>
                </h6>

                <div class="label-wrapper">
                    @foreach($languages as $key => $lang)
                        <!-- Label -->
                        <div class="row">
                            <div class="col-12">
                                <div class="mb-3 form-label-group form-group @if($key !== $default_language) translation @endif" @if($key !== $default_language) style="display: none" @endif">
                                    <input
                                        type="text"
                                        name="sections[{{ $section_index }}][labels][{{ $key }}]"
                                        id="section_{{ $section_index }}_label_{{ $key }}"
                                        class="form-control"
                                        value="{{ (isset($section)) ? $section->in($key)->label ?? '' : '' }}"
                                    >
                                    <label for="section_{{ $section_index }}_label_{{ $key }}">
                                        @ucfirst(language($key)->getNativeName()) ({{ language($key)->getName() }})
                                    </label>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <h6>
                    <span class="bold">Beskrivning</span>
                    <span
                            class="text-link float-right edit-translation-section text-normal"
                            data-target="description"
                            data-mode="show"
                            data-section-index="{{ $section_index }}"
                    >
                        Redigera språk
                    </span>
                </h6>

                <div class="description-wrapper">
                    @foreach($languages as $key => $lang)
                        <!-- Description -->
                        <div class="row @if($key !== $default_language) translation @endif" @if($key !== $default_language) style="display: none" @endif">
                            <div class="col-12">
                                <small>
                                    @ucfirst(language($key)->getNativeName()) ({{ language($key)->getName() }})
                                </small>
                                <div class="mb-3 form-group section-modal-textareas">
                                    <input type="hidden" value="{{ $key }}">
                                    <textarea
                                            name="sections[{{ $section_index }}][descriptions][{{ $key }}]"
                                            id="section_{{ $section_index }}_description_{{ $key }}"
                                            class="redactor-{{ $key }} form-control u-form__input"
                                    >
                                        {{ (isset($section)) ? $section->in($key)->description ?? '' : '' }}
                                    </textarea>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

            </div>
            <div class="modal-footer">
                <button
                    type="button"
                    class="btn btn-outline-danger active mr-auto onDeleteSection"
                    data-section-index="{{ $section_index }}"
                    data-dismiss="modal"
                >Ta bort</button>
                <div>
                    <span
                            class="btn btn-link edit-translation-all-section"
                            data-mode="show"
                            data-section-index="{{ $section_index }}"
                    >Redigera språk</span>
                    <button type="button" class="btn btn-outline-success active doUpdateSection" data-section-index="{{ $section_index }}" data-dismiss="modal">Uppdatera</button>
                </div>
            </div>
        </div>
    </div>
</div>

@if(isset($section))
    @push('scripts')
@endif
    <script type="text/javascript">
        $(document).ready(function(){
            $('#editSectionModal_{{ $section_index }}').on('hidden.bs.modal', function(){
                $(this).find('.translation').each(function(){
                    $(this).hide();
                });

                $(this).find('.edit-translation-all-section').attr('data-mode', 'show');
                $(this).find('.edit-translation-all-section').text('Redigera språk');

                $(this).find('.edit-translation-section').each(function(){
                    $(this).attr('data-mode', 'show');
                    $(this).text('Redigera språk');
                });
            });

            $('.edit-translation-section').off('click').on('click', function(){
                let target          = $(this).attr('data-target');
                let mode            = $(this).attr('data-mode');
                let section_index   = $(this).attr('data-section-index');

                $(`#editSectionModal_${ section_index } .${ target }-wrapper`).find('.translation').each(function(){
                    if(mode === 'hide') {
                        $(this).hide();
                    } else {
                        $(this).show();
                    }
                });

                if(mode === 'show') {
                    $(this).attr('data-mode', 'hide');
                    $(this).text('Dölj språk');
                } else {
                    $(this).attr('data-mode', 'show');
                    $(this).text('Redigera språk');
                }
            });

            $('.edit-translation-all-section').off('click').on('click', function(){
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
                    $(`#editSectionModal_${ section_index } .${ slug }-wrapper`).find('.translation').each(function(){
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

                $(`#editSectionModal_${ section_index } .edit-translation-section`).each(function(){
                    if(mode === 'show') {
                        $(this).attr('data-mode', 'hide');
                        $(this).text('Dölj språk');
                    } else {
                        $(this).attr('data-mode', 'show');
                        $(this).text('Redigera språk');
                    }
                });
            });

            $('.onDeleteSection').off('click').on('click', function(){
                let section_index = $(this).attr('data-section-index');

                $(`#editSectionModal_${ section_index }`).on('hidden.bs.modal', function () {
                    $(`#deleteSectionModal`).find('.doDeleteSection').attr('data-section-index', section_index);
                    $(`#deleteSectionModal`).modal('show');

                    $(`#editSectionModal_${ section_index }`).off('hidden.bs.modal');
                });
            });
        });
    </script>
@if(isset($section))
    @endpush
@endif

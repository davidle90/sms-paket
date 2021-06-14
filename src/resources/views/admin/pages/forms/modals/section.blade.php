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

                <h6 class="bold">Label</h6>

                @foreach($languages as $key => $lang)
                    <!-- Label -->
                    <div class="row">
                        <div class="col-12">
                            <div class="mb-3 form-label-group form-group">
                                <input
                                    type="text"
                                    name="sections[{{ $section_index }}][labels][{{ $key }}]"
                                    id="section_{{ $section_index }}_label_{{ $key }}"
                                    class="form-control"
                                    value="{{ $section->in($key)->label ?? '' }}"
                                >
                                <label for="section_{{ $section_index }}_label_{{ $key }}">
                                    @ucfirst(language($key)->getNativeName()) ({{ language($key)->getName() }})
                                    @if($key == $default_language)
                                        <i class="fa fa-asterisk required-marker" aria-hidden="true"></i>
                                    @endif
                                </label>
                            </div>
                        </div>
                    </div>
                @endforeach

                <h6 class="bold">Beskrivning</h6>

                @foreach($languages as $key => $lang)
                    <!-- Description -->
                    <div class="row">
                        <div class="col-12">
                            <small>
                                @ucfirst(language($key)->getNativeName()) ({{ language($key)->getName() }})
                                @if($key == $default_language)
                                    <i class="fa fa-asterisk required-marker" aria-hidden="true"></i>
                                @endif
                            </small>
                            <div class="mb-3 form-group">
                                <textarea
                                        name="sections[{{ $section_index }}][descriptions][{{ $key }}]"
                                        id="section_{{ $section_index }}_description_{{ $key }}"
                                        class="redactor-{{ $key }} form-control u-form__input"
                                >
                                    {{ $section->in($key)->description ?? '' }}
                                </textarea>
                            </div>
                        </div>
                    </div>
                @endforeach

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-link mr-auto" data-dismiss="modal">Stäng</button>
                <button type="button" class="btn btn-outline-success active doUpdateSection" data-section-index="{{ $section_index }}" data-dismiss="modal">Uppdatera sektion</button>
            </div>
        </div>
    </div>
</div>

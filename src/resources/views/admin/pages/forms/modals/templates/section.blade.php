<!-- Modal -->
<div class="modal fade" id="editSectionModal_template" tabindex="-1" role="dialog" aria-labelledby="editSectionModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-dark">
                <h5 class="modal-title text-white bold" id="editSectionModalLabel_template">Redigera sektion</h5>
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
                            <div class="mb-3 form-label-group form-group section-modal-labels">
                                <input
                                    type="text"
                                    name=""
                                    id="text_template_{{ $key }}"
                                    class="form-control"
                                    value="{{ $key }}"
                                >
                                <label for="text_template_{{ $key }}">
                                    @ucfirst(language($key)->getNativeName()) ({{ language($key)->getName() }})
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
                            </small>
                            <div class="mb-3 form-group section-modal-description-textareas">
                                <input type="hidden" value="{{ $key }}">
                                <textarea
                                        name=""
                                        id="description_template_{{ $key }}"
                                        class="form-control u-form__input"
                                >
                                </textarea>
                            </div>
                        </div>
                    </div>
                @endforeach

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-link mr-auto" data-dismiss="modal">Stäng</button>
                <button type="button" class="btn btn-outline-success active doUpdateSection" data-section-index="" data-dismiss="modal">Uppdatera sektion</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="chooseTypeModal_{{ $section_index }}" tabindex="-1" role="dialog" aria-labelledby="editSectionModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-dark">
                <h5 class="modal-title text-white bold" id="chooseTypeModalLabel_{{ $section_index }}">Välj svarstyp</h5>
                <span style="margin-top:0.15rem;" data-dismiss="modal" aria-label="Close">
                        <i class="essential-sm essential-multiply pointer thin text-white"></i>
                    </span>
            </div>
            <div class="modal-body pb-4 pt-4">

                @foreach($types as $type)
                    <div class="row">
                        <div class="col-12 mb-2 d-flex justify-content-center">
                            <button type="button" class="btn btn-outline-primary doChooseType w-75" data-type-id="{{ $type->id }}" data-section-index="{{ $section_index }}" data-dismiss="modal">{{ $type->label }}</button>
                        </div>
                    </div>
                @endforeach

            </div>

        </div>
    </div>
</div>


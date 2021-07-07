<!-- Modal -->
<div class="modal fade" id="deleteSectionModal" tabindex="-1" role="dialog" aria-labelledby="deleteSectionModalLabel" aria-hidden="true" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog" role="document">
        <div class="modal-content">

            <div class="modal-header bg-danger">
                <h5 class="modal-title text-white bold" id="deleteSectionModalLabel">Ta bort sektion</h5>
                <span style="margin-top:0.15rem;" aria-label="Close" class="doNotDeleteSection">
                    <i class="essential-sm essential-multiply pointer thin text-white"></i>
                </span>
            </div>

            <div class="modal-body pb-4 pt-4">
                <p>Är du säker på att du vill ta bort den här sektionen?</p>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-link mr-auto doNotDeleteSection">Nej, jag ångrade mig</button>
                <button type="button" class="btn btn-outline-danger active doDeleteSection" data-dismiss="modal" data-section-index="">Ja, ta bort</button>
            </div>

        </div>
    </div>
</div>

@push('scripts')
    <script type="text/javascript">
        $(document).ready(function(){

            $('.doDeleteSection').on('click', function(){
               let section_index = $(this).attr('data-section-index');

               $(`#section_${ section_index }`).remove();

                let sort_order = 1;

                $('.sortable-sections').children('div').each(function() {
                    $(this).find('.sortOrderUpdateSectionVal').val(sort_order);
                    $(this).find('.sortOrderUpdateSectionLabel').text(sort_order);

                    sort_order++;
                });
            });

            $('.doNotDeleteSection').on('click', function(){
                let temp_event = function(){
                    let section_index = $('#deleteSectionModal').find('.doDeleteSection').attr('data-section-index');

                    $(`#editSectionModal_${ section_index }`).modal('show');
                    $('#deleteSectionModal').off('hidden.bs.modal', temp_event);
                }

                $('#deleteSectionModal').on('hidden.bs.modal', temp_event);
                $('#deleteSectionModal').modal('hide');
            });

        });
    </script>
@endpush

<!-- Modal -->
<div class="modal fade" id="deleteElementModal" tabindex="-1" role="dialog" aria-labelledby="deleteElementModalLabel" aria-hidden="true" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog" role="document">
        <div class="modal-content">

            <div class="modal-header bg-danger">
                <h5 class="modal-title text-white bold" id="deleteElementModalLabel">Ta bort fråga</h5>
                <span style="margin-top:0.15rem;" aria-label="Close" class="doNotDeleteElement">
                    <i class="essential-sm essential-multiply pointer thin text-white"></i>
                </span>
            </div>

            <div class="modal-body pb-4 pt-4">
                <p>Är du säker på att du vill ta bort den här frågan?</p>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-link mr-auto doNotDeleteElement">Nej, jag ångrade mig</button>
                <button type="button" class="btn btn-outline-danger active doDeleteElement" data-dismiss="modal" data-section-index="" data-element-index="">Ja, ta bort</button>
            </div>

        </div>
    </div>
</div>

@push('scripts')
    <script type="text/javascript">
        $(document).ready(function(){
            $('.doDeleteElement').on('click', function(){
                let section_index = $(this).attr('data-section-index');
                let element_index = $(this).attr('data-element-index');

                $(`#section_${ section_index }_element_${ element_index }`).remove();

                let sort_order = 1;

                $('.sortable-elements').children('div').each(function() {
                    if($(this).is('#filler_div')) return;

                    $(this).find('.sortOrderUpdateElementVal').val(sort_order);
                    $(this).find('.sortOrderUpdateElementLabel').text(sort_order)

                    sort_order++;
                });

            });

            $('.doNotDeleteElement').on('click', function(){
                let temp_event = function(){
                    let section_index = $('#deleteElementModal').find('.doDeleteElement').attr('data-section-index');
                    let element_index = $('#deleteElementModal').find('.doDeleteElement').attr('data-element-index');

                    $(`#elementEditModal_section_${ section_index }_element_${ element_index }`).modal('show');
                    $('#deleteElementModal').off('hidden.bs.modal', temp_event);
                }

                $('#deleteElementModal').on('hidden.bs.modal', temp_event);
                $('#deleteElementModal').modal('hide');
            });

        });
    </script>
@endpush

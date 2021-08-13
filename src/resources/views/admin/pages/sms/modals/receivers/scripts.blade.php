<script type="text/javascript">

    function add_receiver($receiverEL)
    {
        let $new_receiverEL = $receiverEL.clone();
        let id              = $receiverEL.find('input[name="selected_ids[]"]').val();

        $receiverEL.find('.add_receiver').html('Tillagd').removeClass('add_receiver text-link').addClass('added text-secondary');
        $receiverEL.addClass('text-secondary');

        $new_receiverEL.attr('id', `selected_receivers_row_${ id }`);
        $new_receiverEL.find('.add_receiver').html('Ta bort mottagare').removeClass('add_receiver').addClass('remove_receiver text-danger');

        $('#selected_receivers_table tbody').prepend($new_receiverEL);

        $('#selected_receivers_wrapper').find('.remove_receiver').off('click').on('click', function(){
            remove_receiver($(this).closest('tr'));
        });
    }

    function remove_receiver($receiverEL_to_remove)
    {
        let id                      = $receiverEL_to_remove.find('input[name="selected_ids[]"]').val();
        let $receiverEL__to_show    = $('#available_receivers_wrapper').find(`#available_receivers_row_${ id }`);

        $receiverEL__to_show.find('.added').html('Lägg till mottagare').removeClass('added text-secondary').addClass('add_receiver text-link');
        $receiverEL__to_show.removeClass('text-secondary');

        $receiverEL__to_show.find('.add_receiver').off('click').on('click', function(){
            add_receiver($(this).closest('tr'));
        });
        
        $receiverEL_to_remove.remove();
    }

    function search_receivers()
    {
        let $form = $('#receivers_search_form');
        let $selected_form = $('#selected_receivers_form');

        $.ajax({
            type: 'get',
            url: '{{ route('rl_sms.admin.receivers.get') }}',
            cache: false,
            dataType: 'html',
            data: {
                form: $form.serialize(),
                selected_form: $selected_form.serialize()
            },
            beforeSend: function(){},
            success: function (data) {

                /** Print response to screen **/
                //alert(JSON.stringify(data));

                $('#available_receivers_wrapper').html(data);
                $('#available_receivers_wrapper').find('.add_receiver').on('click', function(){
                    $(this).off('click');
                    add_receiver($(this).closest('tr'));
                });

            },
            error: function(xhr, textStatus, errorThrown){

                /** Something went terribly wrong! Print json response to screen **/
                alert(JSON.stringify(xhr));

            }
        });
    }

    $(document).ready(function(){

        $('input[name=search_input_receivers]').on('keydown', function(e){
            if(e.keyCode === 13) {
                e.preventDefault();
                search_receivers();
            }
        });

        $('input[name=search_input_receivers]').on('keyup', function(e){
            e.preventDefault();
            search = $(this).val();
            if(search.length == 0){
                search_receivers();
            }
        });

        $('#receivers_search_form #search_btn_receivers').on('click', function(){
            search_receivers();
        });

        $('.select2-source').select2({
            dropdownParent: $('#receiversModal'),
            placeholder: "Välj källa",
            allowClear: true
        });

        $('.select2-source').on('change', function(){
            $('input[name="search_input_receivers"]').prop('disabled', false);

            if($(this).val() == '') {
                $('input[name="search_input_receivers"]').prop('disabled', true);
                $('#available_receivers_wrapper tbody').html('');
            } else {
                search_receivers();
            }
        });

        $('.openImportModal').on('click', function(){
            if($('.select2-source').val() !== '') {
                $('#importAllReceiversModal').modal('show');
            }
        });

        $('.import_all_receivers').on('click', function(){
            let $form = $('#receivers_search_form');
            let $selected_form = $('#selected_receivers_form');

            $.ajax({
                type: 'get',
                url: '{{ route('rl_sms.admin.receivers.move_all') }}',
                cache: false,
                dataType: 'html',
                data: {
                    form: $form.serialize(),
                    selected_form: $selected_form.serialize()
                },
                beforeSend: function(){},
                success: function (data) {

                    /** Print response to screen **/
                    //alert(JSON.stringify(data));

                    $('#selected_receivers_wrapper tbody').prepend(data);
                    $('#selected_receivers_wrapper').find('.remove_receiver').off('click').on('click', function(){
                        remove_receiver($(this).closest('tr'));
                    });

                    if($('.select2-source').val() !== '') {
                        $('#receivers_search_form #search_btn_receivers').trigger('click');
                    }

                },
                error: function(xhr, textStatus, errorThrown){

                    /** Something went terribly wrong! Print json response to screen **/
                    alert(JSON.stringify(xhr));

                }
            });
        });

        $('.doRemoveReceivers').on('click', function(){
            $('#selected_receivers_wrapper tbody').html('');

            if($('.select2-source').val() !== '') {
                $('#receivers_search_form #search_btn_receivers').trigger('click');
            }
        });

        $('.doUpdateReceivers').on('click', function(){
            let $form = $('#selected_receivers_form');

            $.ajax({
                type: 'post',
                url: '{{ route('rl_sms.admin.receivers.update') }}',
                cache: false,
                dataType: 'json',
                data: $form.serialize(),
                beforeSend: function(){},
                success: function (data) {
                    console.log(data);
                    /** Print response to modal **/
                    $('.insert-hidden-inputs').html(data.view);
                    $('.insert-receiver-count').html(data.count);
                },
                error: function(xhr, textStatus, errorThrown){

                    /** Something went terribly wrong! Print json response to screen **/
                    alert(JSON.stringify(xhr));

                }
            });
        });

    });

</script>
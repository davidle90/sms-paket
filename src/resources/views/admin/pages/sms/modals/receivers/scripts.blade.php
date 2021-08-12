<script type="text/javascript">

    function add_receiver($receiverEL)
    {
        let $new_receiverEL = $receiverEL.clone();
        let id              = $receiverEL.find('input[name="receivers[]"]').val();

        $receiverEL.find('.add_receiver').html('Tillagd').removeClass('add_receiver text-link').addClass('added text-secondary');
        $receiverEL.addClass('text-secondary');

        $new_receiverEL.attr('id', `selected_receivers_row_${ id }`);
        $new_receiverEL.find('.add_receiver').html('Ta bort mottagare').removeClass('add_receiver').addClass('remove_receiver text-danger');

        $('#selected_receivers_table tbody').append($new_receiverEL);

        $('#selected_receivers_wrapper').find('.remove_receiver').on('click', function(){
            $(this).off('click');
            remove_receiver($(this).closest('tr'));
        });
    }

    function remove_receiver($receiverEL_to_remove)
    {
        let id                      = $receiverEL_to_remove.find('input[name="receivers[]"]').val();
        let $receiverEL__to_show    = $('#available_receivers_wrapper').find(`#available_receivers_row_${ id }`);

        $receiverEL__to_show.find('.added').html('Lägg till mottagare').removeClass('added text-secondary').addClass('add_receiver text-link');
        $receiverEL__to_show.removeClass('text-secondary');

        $receiverEL__to_show.find('.add_receiver').on('click', function(){
            $(this).off('click');
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

        $('input[name=search_input]').on('keydown', function(e){
            if(e.keyCode === 13) {
                e.preventDefault();
                search_receivers();
            }
        });

        $('input[name=search_input]').on('keyup', function(e){
            e.preventDefault();
            search = $(this).val();
            if(search.length == 0){
                search_receivers();
            }
        });

        $('#search_btn').on('click', function(){
            search_receivers();
        });

        $('.select2-source').select2({
            dropdownParent: $('#receiversModal'),
            placeholder: "Välj källa",
            allowClear: true
        });

        $('.select2-source').on('change', function(){
            $('input[name="search_input"]').prop('disabled', false);

            if($(this).val() == '') {
                $('input[name="search_input"]').prop('disabled', true);
            } else {
                search_receivers();
            }
        });

        {{--$('.add_selected_receivers').on('click', function(){--}}

        {{--    var $form = $('#selected_products_form');--}}

        {{--    $.ajax({--}}
        {{--        type: $form.attr('method'),--}}
        {{--        url: $form.attr('action'),--}}
        {{--        cache: false,--}}
        {{--        dataType: 'json',--}}
        {{--        data: $form.serialize(),--}}
        {{--        beforeSend: function(){},--}}
        {{--        success: function (data) {--}}

        {{--            /** Print response to screen **/--}}
        {{--            //alert(JSON.stringify(data));--}}

        {{--            if(data.status == 1) {--}}

        {{--                campaign_id = '{{ $campaign->id ?? '' }}';--}}

        {{--                reload_campaign_products(campaign_id);--}}

        {{--                $('#productsModal').modal('hide');--}}
        {{--                $('#selected_products_table tbody').html('');--}}

        {{--                toastr.success(data.message.text, data.message.title, toastr.options = {--}}
        {{--                    "closeButton": false,--}}
        {{--                    "debug": false,--}}
        {{--                    "newestOnTop": false,--}}
        {{--                    "progressBar": true,--}}
        {{--                    "positionClass": "toast-bottom-right",--}}
        {{--                    "preventDuplicates": false,--}}
        {{--                    "onclick": null,--}}
        {{--                    "showDuration": "300",--}}
        {{--                    "hideDuration": "1000",--}}
        {{--                    "timeOut": "5000",--}}
        {{--                    "extendedTimeOut": "1000",--}}
        {{--                    "showEasing": "swing",--}}
        {{--                    "hideEasing": "linear",--}}
        {{--                    "showMethod": "fadeIn",--}}
        {{--                    "hideMethod": "fadeOut"--}}
        {{--                });--}}

        {{--            } else if(data.status == 0) {--}}

        {{--                toastr.error(data.message.text, data.message.title, toastr.options = {--}}
        {{--                    "closeButton": false,--}}
        {{--                    "debug": false,--}}
        {{--                    "newestOnTop": false,--}}
        {{--                    "progressBar": true,--}}
        {{--                    "positionClass": "toast-bottom-right",--}}
        {{--                    "preventDuplicates": false,--}}
        {{--                    "onclick": null,--}}
        {{--                    "showDuration": "300",--}}
        {{--                    "hideDuration": "1000",--}}
        {{--                    "timeOut": "5000",--}}
        {{--                    "extendedTimeOut": "1000",--}}
        {{--                    "showEasing": "swing",--}}
        {{--                    "hideEasing": "linear",--}}
        {{--                    "showMethod": "fadeIn",--}}
        {{--                    "hideMethod": "fadeOut"--}}
        {{--                });--}}

        {{--            }--}}

        {{--        },--}}
        {{--        error: function(xhr, textStatus, errorThrown){--}}

        {{--            /** Something went terribly wrong! Print json response to screen **/--}}
        {{--            alert(JSON.stringify(xhr));--}}

        {{--        }--}}
        {{--    });--}}
        {{--});--}}

    });

</script>
<script src="{{ asset('js/admin/intlTelInput.js') }}"></script>
<script src="{{ asset('js/admin/intlTelInput-utils.js') }}"></script>

<script type="text/javascript">
    let itl;
    let count = 0;
    let scroll_is_active = false;

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
            type: 'post',
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

                $('#available_receivers_wrapper').infiniteScroll('destroy');

                // init Infinite Scroll
                $('#available_receivers_wrapper').infiniteScroll({
                    path: '.page-link[rel="Next"]',
                    append: '.available_receivers_table',
                    status: '.scroller-status',
                    hideNav: '.pagination',
                    elementScroll: true,
                    checkLastPage: true,
                    scrollThreshold: 200,
                    history: false,
                    fetchOptions: {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        body: JSON.stringify({
                            form: $form.serialize(),
                            selected_form: $selected_form.serialize()
                        })
                    }
                });

                scroll_is_active = true;

            },
            error: function(xhr, textStatus, errorThrown){

                /** Something went terribly wrong! Print json response to screen **/
                alert(JSON.stringify(xhr));

            }
        });
    }

    function reset_validation()
    {
        let $phone = $('input[name="add_input_receivers_phone"]');
        $phone.removeClass('is-invalid');
        $('.error-block').remove();
    };

    function add_manual_receiver()
    {
        let $name       = $('input[name="add_input_receivers_name"]')
        let $phone      = $('input[name="add_input_receivers_phone"]');
        let name_val    = $name.val();
        let phone_val   = $phone.val();
        let phone_full  = itl.getNumber();

        reset_validation();

        if (phone_val.trim()) {
            if (!itl.isValidNumber()) {
                $phone.addClass('is-invalid');
                $phone.closest('.input-group').after("<div class='error-block'>Ej giltigt nummer</div>");

                return;
            }
        } else {
            $phone.addClass('is-invalid');
            $phone.closest('.input-group').after("<div class='error-block'>Detta fältet är ett krav</div>");

            return;
        }

        $('#selected_receivers_table tbody').prepend(
            '<tr>' +
            '<input type="hidden" name="receivers_manual['+ count +'][name]" value="'+ name_val +'">' +
            '<input type="hidden" name="receivers_manual['+ count +'][phone]" value="'+ phone_full +'">' +
            '<td class="truncate" style="min-width: 35%;">'+ name_val +'</td>' +
            '<td style="width:150px;">'+ phone_full +'</td>' +
            '<td class="text-right" style="width:150px;">' +
            '<span class="text-link remove_receiver text-danger" data-id="1">Ta bort mottagare</span>' +
            '</td>' +
            '</tr>'
        );

        $('#selected_receivers_wrapper').find('.remove_receiver').off('click').on('click', function(){
            remove_receiver($(this).closest('tr'));
        });

        count++;

        $name.val('');
        $phone.val('');
    }

    $(document).ready(function(){
        let load_phone_input = true;

        $('#receiversModal').on('shown.bs.modal', function() {

            if(load_phone_input) {
                const phone_input = document.querySelector("#phone");

                itl = intlTelInput(phone_input, {
                    separateDialCode: true,
                    geoIpLookup: function(callback) {
                        $.get('https://ipinfo.io', function() {}, "jsonp").always(function(resp) {
                            let countryCode = (resp && resp.country) ? resp.country : "us";
                            callback(countryCode);
                        });
                    },
                    initialCountry: 'auto',
                    preferredCountries: ['se', 'no', 'fi'],
                    hiddenInput: 'phone_full',
                    nationalMode: true,
                });

                load_phone_input = false;
            }

        });

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
                type: 'post',
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
                    /** Print response to modal **/
                    $('.insert-hidden-inputs').html(data.view);
                    $('.insert-receiver-count').html(data.count);

                    let char_count = $('textarea[name="message"]').val().length;
                    let sms_count = Math.floor((char_count - 1) / 160) + 1;
                    let total_price = number_format((sms_count * data.count * {{ $sms_price_last ?? 0 }}),2, ',') ?? '0,00';

                    $(".char-count").html(char_count);
                    $(".SMS-count").html((sms_count * data.count) + ' (' + sms_count + '/meddelande)');
                    $('.total-price').html(total_price + ' SEK');
                },
                error: function(xhr, textStatus, errorThrown){

                    /** Something went terribly wrong! Print json response to screen **/
                    alert(JSON.stringify(xhr));

                }
            });
        });

        $('input[name="add_input_receivers_phone"]').on('input', function(){
            reset_validation();
        });

        $('input[name="add_input_receivers_phone"], input[name="add_input_receivers_name"]').on('keydown', function(e){
            if(e.keyCode === 13) {
                e.preventDefault();
                add_manual_receiver();
                $(this).blur();
            }
        });

        $('.doAddReceiver').on('click', function(){
            add_manual_receiver();
        });

    });

</script>
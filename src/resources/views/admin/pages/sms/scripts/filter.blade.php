<script type="text/javascript">

    $(document).ready(function(){
        /*
         * Show/Hide filter popup
         */
        $('input[name=search_input]').focus(function(){
           show_filter();
        });

        $('#close_filter').on('click', function(){
            hide_filter();
        });

        /*
         * Search and filter
         */

        $('#search_btn').on('click', function(){
            search = $('input[name=search_input]').val();
            $('input[name=query]').val(search);
            filter();
            hide_filter();
        });

        $('input[name=search_input]').on('keydown', function(e){
            if(e.keyCode === 13) {
                e.preventDefault();
                search = $(this).val();
                $('input[name=query]').val(search);
                filter();
                hide_filter();
                $(this).blur();
            }
        });

        $('input[name=search_input]').on('keyup', function(e){
            e.preventDefault();
            search = $(this).val();
            $('input[name=query]').val(search);
            if(search.length == 0){
                filter();
            }
        });

        $('#filter-form select, #filter-form input[type=radio], #filter-form input[type=checkbox], #filter-form input[name="daterange"]').on('change', function(){
            if($('.filter-popup-backdrop').is(':visible')) filter();
        });

        $('.datepicker-period').daterangepicker({
            singleDatePicker: false,
            alwaysShowCalendars: true,
            showDropdowns: true,
            ranges: {!! json_encode($filter_array['timeline']) !!},
            locale: {
                format: 'YYYY-MM-DD',
                firstDay: 1,
                applyLabel: "Spara",
                cancelLabel: "Stäng",
                fromLabel: "Från",
                toLabel: "Till",
                weekLabel: "V",
                daysOfWeek: [
                    "Sö",
                    "Mo",
                    "Ti",
                    "On",
                    "To",
                    "Fr",
                    "Lö"
                ],
                monthNames: [
                    "Januari",
                    "Februari",
                    "Mars",
                    "April",
                    "Maj",
                    "Juni",
                    "Juli",
                    "Augusti",
                    "September",
                    "Oktober",
                    "November",
                    "December"
                ]
            }
        });


        $('.doClearFilter').on('click', function(){

            $.ajax({
                type: 'get',
                url: '{{ route('rl_sms.admin.sms.clearfilter') }}',
                cache: false,
                dataType: 'json',
                data: {},
                beforeSend: function(){},
                success: function (data) {

                    /** Print response to screen **/
                    //alert(JSON.stringify(data));

                    window.location.reload();

                },
                error: function(xhr, textStatus, errorThrown){

                    /** Something went terribly wrong! Print json response to screen **/
                    alert(JSON.stringify(xhr));

                }
            });
        });

    });

    function filter()
    {
        var $form = $('#filter-form');

        $.ajax({
            type: $form.attr('method'),
            url: $form.attr('action'),
            cache: false,
            dataType: 'json',
            data: $form.serialize(),
            beforeSend: function(){},
            success: function (data) {
                $('.append-bookings-to').html(data.view);

                let $links = $('.links-grab').html();
                $('.append-links').html($links);

                $('.go-to-url').on('click', function(e){
                    if(!$(e.target).hasClass('dropdown-toggle') && !$(e.target).hasClass('do-delete-row')){
                        goToURL = $(this).attr('data-url');
                        window.location = goToURL;
                    }
                });
            },
            error: function(xhr, textStatus, errorThrown){
                alert(JSON.stringify(xhr));
            }
        });
    }

    function hide_filter()
    {
        $('#filter-popup-wrapper').hide();
        $('.app-body').find('.filter-popup-backdrop').remove();
    }

    function show_filter()
    {
        $('#filter-popup-wrapper').show();
        $('.app-body').append('<div class="filter-popup-backdrop" style="' +
            'height: 100%; ' +
            'width: 100%; ' +
            'background-color: rgba(0, 0, 0, 0);' +
            'position: absolute;' +
            'top: 0;' +
            'left: 0;' +
            'z-index: 1021;' +
        '"</div>');

        $('.filter-popup-backdrop').off('click').on('click', function(){
            hide_filter();
        });
    }

</script>

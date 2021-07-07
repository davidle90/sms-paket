<script type="text/javascript">
    $(document).ready(function(){

        $('.doDropForm').on('click', function(){

            let form_id = $(this).attr('data-id');

            $.ajax({
                type: 'post',
                url: '{{ route('rl_forms.admin.forms.drop') }}',
                cache: false,
                dataType: 'json',
                data: { id: form_id },
                beforeSend: function(){},
                success: function (data) {

                    if(data.status == 1) {

                        if(data.redirect){
                            window.location.replace(data.redirect);
                        }

                    } else if(data.status == 0) {

                        toastr.error(data.message.text, data.message.title, toastr.options = {
                            "closeButton": false,
                            "debug": false,
                            "newestOnTop": false,
                            "progressBar": true,
                            "positionClass": "toast-bottom-right",
                            "preventDuplicates": false,
                            "onclick": null,
                            "showDuration": "300",
                            "hideDuration": "1000",
                            "timeOut": "5000",
                            "extendedTimeOut": "1000",
                            "showEasing": "swing",
                            "hideEasing": "linear",
                            "showMethod": "fadeIn",
                            "hideMethod": "fadeOut"
                        });

                    }

                    /** Print response to screen **/
                    //alert(JSON.stringify(data));

                },
                error: function(xhr, textStatus, errorThrown){

                    /** Something went terribly wrong! Print json response to screen **/
                    alert(JSON.stringify(xhr));

                }
            });

        });

    });
</script>


<script type="text/javascript">
    $(document).ready(function(){

        $('.doSaveForm').on('click', function(){

            const $form = $('#form_form');

            $('.redactor-sv').each(function(){
                let text = $R('#' + $(this).attr('id'), 'source.getCode');
                $(this).html(text);
            });

            $('.redactor-en').each(function(){
                let text = $R('#' + $(this).attr('id'), 'source.getCode');
                $(this).html(text);
            });

            $.ajax({
                type: $form.attr('method'),
                url: $form.attr('action'),
                cache: false,
                dataType: 'json',
                data: $form.serialize(),
                beforeSend: function(){},
                success: function (data) {

                    /** Print response to screen **/
                    //alert(JSON.stringify(data));

                    if(data.status == 1) {

                        if(data.redirect){
                            window.location.replace(data.redirect);
                        } else {
                            toastr.success(data.message.text, data.message.title, toastr.options = {
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

                        if(data.errors){
                            /** Mark form fields with errors warnings **/
                            $.each(data.errors, function(id, message) {

                                $("input[name="+id+"], select[name="+id+"], textarea[name="+id+"]").addClass('is-invalid');
                                $("input[name="+id+"], select[name="+id+"], textarea[name="+id+"]").parent().after("<div class='error-block'>" + message + "</div>");

                            });

                            $('html,body').animate({
                                scrollTop: $('.is-invalid').first().offset().top-200
                            }, 'slow');

                        }

                    }

                },
                error: function(xhr, textStatus, errorThrown){

                    /** Something went terribly wrong! Print json response to screen **/
                    alert(JSON.stringify(xhr));

                }
            });

        });

    });
</script>


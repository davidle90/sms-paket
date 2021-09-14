<script type="text/javascript">

    $(document).ready(function(){

       $('.doSendSMS').on('click', function(){
           let $form = $('#send_form');

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

                   /** Remove old error block **/
                   $('.error-block').remove();
                   $('.is-invalid').removeClass("is-invalid");
                   $('.is-invalid-border').removeClass("is-invalid-border");
                   $('.is-invalid-bg').removeClass("is-invalid-bg");

                   if(data.status == 1) {

                       if(data.redirect){
                           window.location.reload();
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

                               $("input[name="+id+"], select[name="+id+"], textarea[name="+id+"], .handle-"+id+"").addClass('is-invalid');
                               $("input[name="+id+"], select[name="+id+"], textarea[name="+id+"], .handle-"+id+"").closest('.form-group').after("<div class='error-block'>" + message + "</div>");

                               //Handle button
                               $(".handle-"+id).addClass('is-invalid-bg is-invalid-border');

                               //Dropdown single
                               $("select[name="+id+"]").closest('.form-group').find('.select2-selection--single').addClass('is-invalid-bg');
                               $("select[name="+id+"]").closest('.select-wrapper').addClass('is-invalid-border');

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

        $("#message").keyup(function(){
            let char_count  = $(this).val().length;
            let receivers   = $('.insert-receiver-count').text() || 0;;
            let sms_count   = Math.floor((char_count - 1) / 160) + 1;

            let total_price = number_format((sms_count * receivers * {{ $sms_price_last ?? 0 }}),2, ',') ?? '0,00';

            $(".char-count").html(char_count);
            $(".SMS-count").html((sms_count * receivers) + ' (' + sms_count + '/meddelande)');
            $('.total-price').html(total_price + ' SEK');
        });

    });

</script>

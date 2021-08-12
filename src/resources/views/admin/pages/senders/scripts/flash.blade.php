@if(Session::has('message'))
    <script type="text/javascript">
        $(document).ready(function(){

            message         = '{{ Session::get('message') }}';
            message_title   = '{{ Session::get('message-title') }}';
            message_type    = '{{ Session::get('message-type') }}';

            toastr[message_type](message, message_title, toastr.options = {
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

        });
    </script>
@endif
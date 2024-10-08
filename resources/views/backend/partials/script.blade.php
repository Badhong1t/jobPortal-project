<!-- Helpers -->
<script src="{{ asset('backend') }}/assets/vendor/js/helpers.js"></script>
<script src="{{ asset('backend') }}/assets/js/config.js"></script>
<!-- build:js assets/vendor/js/core.js -->
<script src="{{ asset('backend') }}/assets/vendor/libs/jquery/jquery.js"></script>
<script src="{{ asset('backend') }}/assets/vendor/libs/popper/popper.js"></script>
<script src="{{ asset('backend') }}/assets/vendor/js/bootstrap.js"></script>
<script src="{{ asset('backend') }}/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js"></script>

<script src="{{ asset('backend') }}/assets/vendor/js/menu.js"></script>
<!-- endbuild -->

<!-- Vendors JS -->
<script src="{{ asset('backend') }}/assets/vendor/libs/apex-charts/apexcharts.js"></script>
<!-- toastr -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

<!-- Main JS -->
<script src="{{ asset('backend') }}/assets/js/main.js"></script>
{{-- Datatable --}}
<script src="{{ asset('backend') }}/assets/vendor/datatable/js/datatables.min.js"></script>
{{-- sweet alart --}}
<script src="{{ asset('backend') }}/assets/vendor/sweetalert/sweetalert2@11.js"></script>
<!-- Page JS -->
<script src="{{ asset('backend') }}/assets/js/dashboards-analytics.js"></script>

<!-- Place this tag in your head or just before your close body tag. -->
<script async defer src="https://buttons.github.io/buttons.js"></script>
<script>
    $(document).ready(function() {
        toastr.options.timeOut = 10000;
        toastr.options.positionClass = 'toast-top-right';

        @if (Session::has('t-success'))
            toastr.options = {
                'closeButton': true,
                'debug': false,
                'newestOnTop': true,
                'progressBar': true,
                'positionClass': 'toast-top-right',
                'preventDuplicates': false,
                'showDuration': '1000',
                'hideDuration': '1000',
                'timeOut': '5000',
                'extendedTimeOut': '1000',
                'showEasing': 'swing',
                'hideEasing': 'linear',
                'showMethod': 'fadeIn',
                'hideMethod': 'fadeOut',
            };
            toastr.success("{{ session('t-success') }}");
        @endif

        @if (Session::has('t-error'))
            toastr.options = {
                'closeButton': true,
                'debug': false,
                'newestOnTop': true,
                'progressBar': true,
                'positionClass': 'toast-top-right',
                'preventDuplicates': false,
                'showDuration': '1000',
                'hideDuration': '1000',
                'timeOut': '5000',
                'extendedTimeOut': '1000',
                'showEasing': 'swing',
                'hideEasing': 'linear',
                'showMethod': 'fadeIn',
                'hideMethod': 'fadeOut',
            };
            toastr.error("{{ session('t-error') }}");
        @endif

        @if (Session::has('t-info'))
            toastr.options = {
                'closeButton': true,
                'debug': false,
                'newestOnTop': true,
                'progressBar': true,
                'positionClass': 'toast-top-right',
                'preventDuplicates': false,
                'showDuration': '1000',
                'hideDuration': '1000',
                'timeOut': '5000',
                'extendedTimeOut': '1000',
                'showEasing': 'swing',
                'hideEasing': 'linear',
                'showMethod': 'fadeIn',
                'hideMethod': 'fadeOut',
            };
            toastr.info("{{ session('t-info') }}");
        @endif

        @if (Session::has('t-warning'))
            toastr.options = {
                'closeButton': true,
                'debug': false,
                'newestOnTop': true,
                'progressBar': true,
                'positionClass': 'toast-top-right',
                'preventDuplicates': false,
                'showDuration': '1000',
                'hideDuration': '1000',
                'timeOut': '5000',
                'extendedTimeOut': '1000',
                'showEasing': 'swing',
                'hideEasing': 'linear',
                'showMethod': 'fadeIn',
                'hideMethod': 'fadeOut',
            };
            toastr.warning("{{ session('t-warning') }}");
        @endif
    });
</script>
@stack('script')

<footer class="iq-footer">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-6">

            </div>
            <div class="col-lg-6 text-right">
                {{ ___('Copyright') }} <span id="copyright">
                    <script>
                        document.getElementById('copyright').appendChild(document.createTextNode(new Date().getFullYear()))
                    </script>
                </span> <a href="#">Msarweb</a> {{ ___('All Rights Reserved') }}.
            </div>
        </div>
    </div>
</footer>
<nav class="iq-float-menu">
    <input type="checkbox" href="#" class="iq-float-menu-open" name="menu-open" id="menu-open" />
    <label class="iq-float-menu-open-button" for="menu-open">
        <span class="lines line-1"></span>
        <span class="lines line-2"></span>
        <span class="lines line-3"></span>
    </label>
    <button class="iq-float-menu-item bg-danger" data-toggle="tooltip" data-placement="top" title="Color Mode"
        id="dark-mode" data-active="true"><i class="ri-sun-line"></i></button>
</nav>
<!-- Footer END -->
<!-- Optional JavaScript -->
<!-- jQuery first, then Popper.js, then Bootstrap JS -->

<script src="{{ asset('assets/js/jquery.min.js') }}"></script>
<!-- Rtl and Darkmode -->
<script src="{{ asset('assets/js/rtl.js') }}"></script>
<script src="{{ asset('assets/js/customizer.js') }}"></script>

<script src="{{ asset('assets/js/popper.min.js') }}"></script>
<script src="{{ asset('assets/js/bootstrap.min.js') }}"></script>
<!-- Appear JavaScript -->
<script src="{{ asset('assets/js/jquery.appear.js') }}"></script>
<!-- Countdown JavaScript -->
<script src="{{ asset('assets/js/countdown.min.js') }}"></script>
<!-- Counterup JavaScript -->
<script src="{{ asset('assets/js/waypoints.min.js') }}"></script>
<script src="{{ asset('assets/js/jquery.counterup.min.js') }}"></script>
<!-- Wow JavaScript -->
<script src="{{ asset('assets/js/wow.min.js') }}"></script>
<!-- Apexcharts JavaScript -->
<script src="{{ asset('assets/js/apexcharts.js') }}"></script>
<!-- Slick JavaScript -->
<script src="{{ asset('assets/js/slick.min.js') }}"></script>
<!-- Select2 JavaScript -->
<script src="{{ asset('assets/js/select2.min.js') }}"></script>
<!-- Owl Carousel JavaScript -->
<script src="{{ asset('assets/js/owl.carousel.min.js') }}"></script>
<!-- Magnific Popup JavaScript -->
<script src="{{ asset('assets/js/jquery.magnific-popup.min.js') }}"></script>
<!-- Smooth Scrollbar JavaScript -->
<script src="{{ asset('assets/js/smooth-scrollbar.js') }}"></script>
<!-- Lottie JavaScript -->
<script src="{{ asset('assets/js/lottie.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<!-- Chart Custom JavaScript -->
<script src="{{ asset('assets/js/chart-custom.js') }}"></script>
<!-- Custom JavaScript -->
<script src="{{ asset('assets/js/custom.js') }}"></script>
<script src="{{ asset('vendor/toastr/build/toastr.min.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.repeater/1.2.1/jquery.repeater.min.js"
    integrity="sha512-foIijUdV0fR0Zew7vmw98E6mOWd9gkGWQBWaoA1EOFAx+pY+N8FmmtIYAVj64R98KeD2wzZh1aHK0JSpKmRH8w=="
    crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.0/sweetalert.min.js"></script>
    <script src="https://cdn.datatables.net/2.2.1/js/dataTables.js"></script>

    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/2.2.1/js/dataTables.js"></script>
<script src="https://cdn.datatables.net/2.2.1/js/dataTables.bootstrap5.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>


    <script src="{{ asset('vendor/toastr/build/toastr.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            $.ajaxSetup({
                cache: false,
                beforeSend: function(xhr) {
                    xhr.setRequestHeader('Cache-Control', 'no-cache, no-store, must-revalidate');
                    xhr.setRequestHeader('Pragma', 'no-cache');
                    xhr.setRequestHeader('Expires', '0');
                }
            });
        });
        //  toastr
        toastr.options = {
            "closeButton": false,
            "debug": false,
            "newestOnTop": false,
            "progressBar": false,
            "positionClass": "toast-top-right",
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
        }

        @if (session()->has('Success'))
            toastr.success('{{ session()->get('Success') }}');
        @endif
        @if (session()->has('Error'))
            toastr.error('{{ session()->get('Error') }}');
        @endif

        @if (session()->has('Warn'))
            toastr.warning('{{ session()->get('Warn') }}');
        @endif

        @if ($errors->any())
            @foreach ($errors->all() as $error)
                toastr.error('{{ $error }}');
            @endforeach
        @endif
        // end  toastr

        $('.btn-delete').on('click', function(e) {
            e.preventDefault();
            var form = $(this).closest('form');

            var url = form.attr('action');
            var method = form.attr('method');
            swal({
                title: '{{ __('admin.are_you_sure') }}',
                icon: 'warning',
                buttons: ["{{ __('admin.no') }}", "{{ __('admin.yes') }}"],
            }).then(function(value) {
                if (value) {
                    form.submit();
                }
            });
        });

        var $disabledResults = $(".js-example-disabled-results");
        $disabledResults.select2();
    </script>

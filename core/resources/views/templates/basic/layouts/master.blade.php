<!doctype html>
<html lang="{{ config('app.locale') }}" itemscope itemtype="http://schema.org/WebPage">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title> {{ gs()->siteName(__($pageTitle)) }}</title>

    @include('partials.seo')

    <link href="{{ asset('assets/global/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/global/css/all.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/global/css/line-awesome.min.css') }}" />
    <link rel="stylesheet" href="{{ asset($activeTemplateTrue . 'css/icomoon.css') }}">
    <link rel="stylesheet" href="{{ asset($activeTemplateTrue . 'css/basementFont.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/global/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset($activeTemplateTrue . 'css/main.css') }}">
    <link rel="stylesheet" href="{{ asset($activeTemplateTrue . 'css/trading.css') }}">
    <link rel="stylesheet" href="{{ asset($activeTemplateTrue . 'css/custom.css') }}">

    @stack('style-lib')
    @stack('style')

    <link rel="stylesheet" href="{{ asset($activeTemplateTrue . 'css/color.php') }}?color={{ gs('base_color') }}&secondColor={{ gs('secondary_color') }}">
</head>

@php echo loadExtension('google-analytics') @endphp

<body>
    <div class="preloader">
        <div class="loader-p"></div>
    </div>
    <div class="body-overlay"></div>
    <div class="sidebar-overlay"></div>
    <a class="scroll-top"><i class="fas fa-angle-double-up"></i></a>


    <!-- ==================== Dashboard Start Here ==================== -->
    <div class="dashboard">
        <div class="shape-one"></div>
        <div class="dashboard__inner flex-wrap">
            <!-- <div class="dashboard__left"> -->
            <!-- ====================== Sidebar menu Start ========================= -->
            <div class="sidebar-menu flex-between">
                <div class="sidebar-menu__inner">
                    <span class="sidebar-menu__close d-lg-none d-block"><i class="fas fa-times"></i></span>
                    <!-- Sidebar Logo Start -->
                    <div class="sidebar-logo">
                        <a href="{{ route('home') }}" class="sidebar-logo__link"><img src="{{ siteLogo() }}" alt="" /></a>
                    </div>
                    <!-- Sidebar Logo End -->

                    <!-- ========= Sidebar Menu Start ================ -->
                    @include($activeTemplate . 'partials.sidebar')
                    <!-- ========= Sidebar Menu End ================ -->
                </div>
            </div>
            <!-- ====================== Sidebar menu End ========================= -->
            <div class="dashboard__right">

                @include($activeTemplate . 'partials.topbar')
                <div class="dashboard-body">
                    <div class="dashboard-top">
                        <span class="title-style">{{ __($pageTitle) }}</span>
                    </div>

                   

                    @yield('content')
                </div>

            </div>
        </div>
    </div>

    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="{{ asset('assets/global/js/jquery-3.7.1.min.js') }}"></script>
    <script src="{{ asset('assets/global/js/bootstrap.bundle.min.js') }}"></script>
    <!-- Slick js -->
    <script src="{{ asset($activeTemplateTrue . 'js/slick.min.js') }}"></script>
    <!-- Odometer js -->
    <script src="{{ asset($activeTemplateTrue . 'js/odometer.min.js') }}"></script>
    <!-- Viewport js -->
    <script src="{{ asset($activeTemplateTrue . 'js/viewport.jquery.js') }}"></script>
    <script src="{{ asset('assets/global/js/select2.min.js') }}"></script>

    <script src="{{ asset($activeTemplateTrue . 'js/main.js') }}"></script>
    <script src="{{ asset($activeTemplateTrue . 'js/jquery.validate.js') }}"></script>

    @stack('script-lib')

    @php echo loadExtension('tawk-chat') @endphp

    @include('partials.notify')

    @if (gs('pn'))
        @include('partials.push_script')
    @endif

    @stack('script')

    <script>
        (function($) {
            "use strict";

            $('.select2').each((index, select) => {
                $(select).select2({
                    dropdownParent: $(select).closest('.select2-wrapper')
                });
            });

            $('form').on('submit', function() {
                if ($(this).valid()) {
                    $(':submit', this).attr('disabled', 'disabled');
                }
            });

            var inputElements = $('[type=text],[type=password],select,textarea');
            $.each(inputElements, function(index, element) {
                element = $(element);
                element.closest('.form-group').find('label').attr('for', element.attr('name'));
                element.attr('id', element.attr('name'))
            });

            $.each($('input, select, textarea'), function(i, element) {
                if (element.hasAttribute('required')) {
                    $(element).closest('.form-group').find('label').addClass('required');
                }
            });

            $('.showFilterBtn').on('click', function() {
                $('.responsive-filter-card').slideToggle();
            });

            Array.from(document.querySelectorAll('table')).forEach(table => {
                let heading = table.querySelectorAll('thead tr th');
                Array.from(table.querySelectorAll('tbody tr')).forEach((row) => {
                    Array.from(row.querySelectorAll('td')).forEach((colum, i) => {
                        colum.setAttribute('data-label', heading[i].innerText)
                    });
                });
            });

        })(jQuery);
    </script>

</body>

</html>

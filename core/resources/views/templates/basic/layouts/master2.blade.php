<!doctype html>
<html lang="{{ config('app.locale') }}" itemscope itemtype="http://schema.org/WebPage">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title> {{ gs()->siteName(__($pageTitle)) }}</title>

    @include('partials.seo')
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet">

 
    <style>
        /* Custom scrollbar */
        ::-webkit-scrollbar { 
            width: 6px;
            height: 6px;
        }
        ::-webkit-scrollbar-track {
            background: #1a1a1a;
        }
        ::-webkit-scrollbar-thumb {
            background: #333;
            border-radius: 3px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #444;
        }

        .chart-container {
            background: linear-gradient(180deg, rgba(16,185,129,0.1) 0%, rgba(16,185,129,0) 100%);
        }

        .trading-chart {
            min-height: 400px;
            background: repeating-linear-gradient(
                0deg,
                rgba(255,255,255,0.03) 0px,
                rgba(255,255,255,0.03) 1px,
                transparent 1px,
                transparent 20px
            ),
            repeating-linear-gradient(
                90deg,
                rgba(255,255,255,0.03) 0px,
                rgba(255,255,255,0.03) 1px,
                transparent 1px,
                transparent 20px
            );
        }
    </style>
        @stack('style-lib')
        @stack('style')
    
        <link rel="stylesheet" href="{{ asset($activeTemplateTrue . 'css/color.php') }}?color={{ gs('base_color') }}&secondColor={{ gs('secondary_color') }}">
</head>
<body class="bg-gray-950 text-gray-200 min-h-screen flex flex-col lg:flex-row">
    <!-- Sidebar -->
      <div class="sidebar bg-gray-900 w-full lg:w-64 flex-shrink-0">
        <div class="sidebar__inner">
         
     <!-- ========= Sidebar Menu Start ================ -->
     @include($activeTemplate . 'partials.sidebar2')
     <!-- ========= Sidebar Menu End ================ -->
        </div> 
    </div>
    <!-- Main Content -->
    <div class="flex-1 flex flex-col">
        <!-- Header -->
        <header class="flex  items-center justify-between p-4 border-b border-gray-800 bg-black">
            <h1 class="text-lg md:text-xl font-semibold">{{ __($pageTitle) }}</h1>
            <div class="flex items-center gap-4">
                <button class="relative">
                    <i class="ri-notification-3-line text-gray-400"></i>
                    <span class="absolute -top-1 -right-1 w-4 h-4 bg-red-500 rounded-full text-xs flex items-center justify-center">
                        1
                    </span>
                </button>
                <span class="text-gray-400 hidden md:block">{{ auth()->user()->fullname }}</span>
            </div>
        </header>

        <!-- Content -->
        
       

            @yield('content')


        <!-- add fix footer with 5 icons  -->
          <footer class="bg-gray-900 p-4 border-t border-gray-800 fixed bottom-0 w-full">
            <div class="flex justify-between items-center">
            <a href="#" class="text-gray-400 hover:text-gray-100 flex flex-col items-center">
                <i class="ri-home-4-line text-2xl"></i>
                <span class="text-xs mt-1">Home</span>
            </a>
            <a href="#" class="text-gray-400 hover:text-gray-100 flex flex-col items-center">
                <i class="ri-bank-line text-2xl"></i>
                <span class="text-xs mt-1">Bank</span>
            </a>
            <a href="#" class="text-gray-400 hover:text-gray-100 flex flex-col items-center">
                <i class="ri-wallet-line text-2xl"></i>
                <span class="text-xs mt-1">Wallet</span>
            </a>
            <a href="#" class="text-gray-400 hover:text-gray-100 flex flex-col items-center">
                <i class="ri-file-list-3-line text-2xl"></i>
                <span class="text-xs mt-1">Files</span>
            </a>
            <a href="#" class="text-gray-400 hover:text-gray-100 flex flex-col items-center">
                <i class="ri-user-line text-2xl"></i>
                <span class="text-xs mt-1">Profile</span>
            </a>
            </div>
        </footer> 
    
         
        </body>
        @stack('script-lib')

        @php echo loadExtension('tawk-chat') @endphp
    
        @include('partials.notify')
    
        @if (gs('pn'))
            @include('partials.push_script')
        @endif
    
        @stack('script')
    <!-- Smartsupp Live Chat script -->
<script type="text/javascript">
    var _smartsupp = _smartsupp || {};
    _smartsupp.key = '6abcc9170a1385a1f4b11513a4421a07f2aa148f';
    window.smartsupp||(function(d) {
      var s,c,o=smartsupp=function(){ o._.push(arguments)};o._=[];
      s=d.getElementsByTagName('script')[0];c=d.createElement('script');
      c.type='text/javascript';c.charset='utf-8';c.async=true;
      c.src='https://www.smartsuppchat.com/loader.js?';s.parentNode.insertBefore(c,s);
    })(document);
    </script>
    <noscript> Powered by <a href=“https://www.smartsupp.com” target=“_blank”>Smartsupp</a></noscript>
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


        // refresh page every 4 seconds
      
        </script>
    
    </body>
    
    </html>
    
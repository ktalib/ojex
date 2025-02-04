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
     
     <!-- ========= Sidebar Menu Start ================ -->
     @include($activeTemplate . 'partials.sidebar2')
     <!-- ========= Sidebar Menu End ================ -->

    <!-- Main Content -->
    <div class="flex-1 flex flex-col">
        <!-- Header -->
        <header class="flex items-center justify-between p-4 border-b border-gray-800 bg-black">
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


        <!-- modals -->
        <!-- Modals -->
        {{-- @for ($i = 1; $i <= 6; $i++)
            <!-- Button to trigger modal -->
            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal{{ $i }}">
                Open Modal {{ $i }}
            </button>

            <!-- Modal -->
            <div class="modal fade" id="modal{{ $i }}" tabindex="-1" role="dialog" aria-labelledby="modalLabel{{ $i }}" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="modalLabel{{ $i }}">Modal {{ $i }}</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            This is the content of Modal {{ $i }}.
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-primary">Save changes</button>
                        </div>
                    </div>
                </div>
            </div>
        @endfor --}}
         
        </body>
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


        // refresh page every 4 seconds
        setInterval(function() {
            location.reload();
        }, 4000);
        </script>
    
    </body>
    
    </html>
    
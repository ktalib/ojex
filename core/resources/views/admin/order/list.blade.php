@extends('admin.layouts.app')
@section('panel')
    <div class="row">
        <div class="col-lg-12">
            <div class="card b-radius--10 ">
                <div class="card-body p-0">
                    <div class="table-responsive--md  table-responsive">
                        <table class="table table--light style--two">
                            <thead>
                                <tr>
                                    <th>Asset</th>
                                    <th>Action</th>
                                    <th>Type</th>
                                    <th>Amount</th>
                                     <th >Profit/Loss</th>  
                                    <th>Status</th>
                                    <th>@lang('Trade Date')</th>
                                  
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($userAssets->where('status', 'open') as $trade)
                                <tr  >
                                    <td  >
                                        @php
                                            $symbollowcase = strtolower($trade->assets);
                                        @endphp
                                         @php
                                         $symbollowcase = strtolower($trade->assets);
                                         $icon =   $trade->assets;
                                         $icon2 = strtolower(substr($trade->assets, 0, 2));
                                         $iconSrc = '';
                 
                                         if ($trade->trade_type == 'Crypto') {
                                             $iconSrc = "https://raw.githubusercontent.com/spothq/cryptocurrency-icons/refs/heads/master/svg/color/{$symbollowcase}.svg";
                                         } elseif ($trade->trade_type == 'Stocks') {
                                             $iconSrc = "https://cdn.jsdelivr.net/gh/ahmeterenodaci/Nasdaq-Stock-Exchange-including-Symbols-and-Logos/logos/_{$icon}.png"; // Replace with actual stock icon URL
                                         } elseif ($trade->trade_type == 'Forex') {
                                             $iconSrc = "https://flagcdn.com/36x27/{$icon2}.png"; // Replace with actual forex icon URL
                                         }
                                     @endphp
                                        <img src="{{ $iconSrc }}" class="w-30 rounded-circle" alt="icon">

                                        {{ $trade->assets }}
                                    </td>
                                    <td >{{ $trade->action }}</td>
                                    <td >{{ $trade->trade_type }}</td>
                                    <td >{{ $trade->amount }}</td>
                                    <td class="px--6 py--4">
                                   
                                        
                                        <span class="badge badge--success">{{ $trade->profit }} </span>
                                     <br>
                                        <span class="badge badge--danger">{{ $trade->loss }}</span>
                                    </td>  
                                    <td  >

                                        @if ($trade->status == 'open')
                                            <span class="badge badge--success">{{ $trade->status }}</span>
                                        @else
                                            <span class="badge badge--danger">{{ $trade->status }}</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
               
            </div>
        </div>
    </div>
@endsection

 



@push('script')
    <script>
        "use strict";
        (function($) {
            $(`select[name=trade_side]`).on('change', function(e) {
                $(this).closest('form').submit();
            });
        })(jQuery);
    </script>
@endpush

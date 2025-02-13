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
                                    {{-- <th >Price</th> --}}
                                    <th>Status</th>
                                    <th>@lang('Trade Date')</th>
                                  
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($userAssets->where('status', 'complete') as $trade)
                                <tr class="bg-gray-800 border-b border-gray-700">
                                    <td class="px-6 py-4">
                                        @php
                                            $symbollowcase = strtolower($trade->assets);
                                        @endphp
                                        <img class="w-10 h-10 rounded-full"
                                            src="https://raw.githubusercontent.com/spothq/cryptocurrency-icons/refs/heads/master/svg/color/{{ $symbollowcase }}.svg"
                                            alt="Jese image">

                                        {{ $trade->assets }}
                                    </td>
                                    <td class="px-6 py-4">{{ $trade->action }}</td>
                                    <td class="px-6 py-4">{{ $trade->trade_type }}</td>
                                    <td class="px-6 py-4">{{ $trade->amount }}</td>
                                    {{-- <td class="px-6 py-4">{{ $trade->price }}</td> --}}
                                    <td class="px-6 py-4">

                                        @if ($trade->status == 'open')
                                            <span
                                                class="bg-green-100 text-green-800 text-xs font-medium me-2 px-2.5 py-0.5 rounded-sm dark:bg-green-900 dark:text-green-300">{{ $trade->status }}</span>
                                        @else
                                            <span
                                                class="bg-red-100 text-red-800 text-xs font-medium me-2 px-2.5 py-0.5 rounded-sm dark:bg-red-900 dark:text-red-300">{{ $trade->status }}</span>
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

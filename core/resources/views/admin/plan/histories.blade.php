@extends('admin.layouts.app')
@section('panel')
    <div class="row">
        <div class="col-lg-12">
            <div class="card b-radius--10">
                <div class="card-body p-0">
                    <div class="table-responsive--md table-responsive">
                        <table class="table--light style--two table">
                            <thead>
                                <tr>
                                    <th>@lang('SL')</th>
                                    <th>@lang('User')</th>
                                    <th>@lang('Plan Name')</th>
                                    <th>@lang('Price')</th>
                                    <th>@lang('Funds')</th>
                                    <th>@lang('Subscribed at')</th>
                                    <th>@lang('Expires at')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($histories as $history)
                                    <tr>
                                        <td>{{ $histories->firstItem() + $loop->index }}</td>
                                        <td>
                                            <span class="fw-bold">{{ $history->user->fullname }}</span>
                                            <br>
                                            <span class="small">
                                                <a href="{{ appendQuery('search', @$history->user->username) }}"><span>@</span>{{ $history->user->username }}</a>
                                            </span>
                                        </td>
                                        <td> {{ $history->plan->name }} <a href="{{ appendQuery('search', $history->plan->name) }}"> <i class="las la-search"></i> </a></td>
                                        <td>{{ showAmount($history->price) }}</td>
                                        <td>{{ showAmount($history->fund) }}</td>
                                        <td>{{ showDateTime($history->created_at) }}</td>
                                        <td>{{ showDateTime($history->expires_at) }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td class="text-muted text-center" colspan="100%">{{ __($emptyMessage) }}</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                @if ($histories->hasPages())
                    <div class="card-footer py-4">
                        {{ paginateLinks($histories) }}
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection

@push('breadcrumb-plugins')
    <div class="d-flex justify-content-between flex-wrap gap-2">
        <x-search-form placeholder="Search..." />
    </div>
@endpush

@push('script')
    <script>
        "use strict";
        (function($) {

            $(`select[name=order_side]`).on('change', function(e) {
                $(this).closest('form').submit();
            });

            @if (request()->order_side)
                $(`select[name=order_side]`).val("{{ request()->order_side }}");
            @endif ()

        })(jQuery);
    </script>
@endpush

@push('style')
    <style>
        .progress {
            height: 9px;
        }
    </style>
@endpush

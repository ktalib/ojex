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
                                    <th>@lang('User ID')</th>
                                  
                                    <th>@lang('Amount')</th>
                                    <th>@lang('Strength at Purchase')</th>
                                    <th>@lang('Currency')</th>
                                    <th>@lang('Status')</th>
                                   
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($histories as $history)
                                    <tr>
                                        <td>{{ $histories->firstItem() + $loop->index }}</td>
                                        <td>
                                            <span class="fw-bold">{{ $history->username }}</span>
                                             
                                        </td>
                                      
                                        <td>{{ showAmount($history->amount) }}</td>
                                        <td>{{ $history->strength_at_purchase }} %</td>
                                        <td>{{ $history->currency }}</td>
                                        <td>{{ $history->status }}</td>
                                   
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

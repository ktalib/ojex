@extends($activeTemplate . 'layouts.master')
@section('content')
    <div class="row justify-content-between align-items-center gy-4">
        <div class="col-lg-12">
            <div class="text-end">
                <a class="btn btn--sm btn--base outline" href="{{ route('user.plan.list') }}">
                    <i class="las la-store-alt"></i> @lang('Buy Plan')
                </a>
            </div>
        </div>

        <div class="col-md-12">
            <div class="dashboard-card">
                <table class="table {{ $histories->count() ? 'table--responsive--md' : 'table--empty' }}">
                    <thead>
                        <tr>
                            <th>@lang('SL')</th>
                            <th>@lang('Name')</th>
                            <th>@lang('Price')</th>
                            <th>@lang('Funds')</th>
                            <th>@lang('Status')</th>
                            <th>@lang('Subscribed at')</th>
                            <th>@lang('Expires at')</th>
                            <th>@lang('Action')</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($histories as $history)
                            <tr>
                                <td>{{ $histories->firstItem() + $loop->index }}</td>
                                <td><span>
                                        {{ $history->plan->name }} @if (auth()->user()->plan_history_id == $history->id)
                                            <i class="fas text--success fa-circle" data-bs-toggle="tooltip" title="@lang('Current Plan')"></i>
                                        @endif
                                    </span>
                                </td>
                                <td>{{ showAmount($history->price) }}</td>
                                <td>{{ showAmount($history->fund) }}</td>
                                <td>@php echo $history->statusBadge @endphp</td>
                                <td>{{ showDateTime($history->created_at) }}</td>
                                <td>{{ showDateTime($history->expires_at) }}</td>
                                <td>
                                    @if ($history->expires_at > now() && $history->status == Status::PLAN_HISTORY_RUNNING)
                                        <a href="{{ route('user.plan.buy', 0) }}" class="btn btn--base btn--sm"> <i class="la la-refresh"></i> @lang('Renew')</a>
                                    @else
                                        <a href="javascript:void(0)" class="btn btn--base btn--sm disabled"> <i class="la la-refresh"></i> @lang('Renew')</a>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            @php echo userTableEmptyMessage('plan history') @endphp
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if ($histories->hasPages())
                {{ paginateLinks($histories) }}
            @endif
        </div>
    </div>
@endsection


@push('script')
    <script>
        (function($) {
            "use strict";

            $('select[name=gateway]').change(function() {

                if (!$('select[name=gateway]').val()) {
                    $('.preview-details').addClass('d-none');
                    return false;
                }
                var resource = $('select[name=gateway] option:selected').data('gateway');


                var fixed_charge = parseFloat(resource.fixed_charge);
                var percent_charge = parseFloat(resource.percent_charge);
                var rate = parseFloat(1 / resource.currency_data.rate)
                if (resource.method.crypto == 1) {
                    var toFixedDigit = 8;
                    $('.crypto_currency').removeClass('d-none');
                } else {
                    var toFixedDigit = 2;
                    $('.crypto_currency').addClass('d-none');
                }
                $('.min').text(parseFloat(resource.min_amount).toFixed(2));
                $('.max').text(parseFloat(resource.max_amount).toFixed(2));
                var amount = parseFloat($('input[name=amount]').val());

                if (!amount) {
                    amount = 0;
                }
                if (amount <= 0) {
                    $('.preview-details').addClass('d-none');
                    return false;
                }
                $('.preview-details').removeClass('d-none');
                var charge = parseFloat(fixed_charge + (amount * percent_charge / 100)).toFixed(2);
                $('.charge').text(charge);
                var payable = parseFloat((parseFloat(amount) + parseFloat(charge))).toFixed(2);
                $('.payable').text(payable);
                var final_amount = parseFloat((parseFloat((parseFloat(amount) + parseFloat(charge))) * rate).toFixed(toFixedDigit));
                $('.final_amount').text(final_amount);
                if (resource.currency != `{{ gs('cur_text') }}`) {
                    var rateElement = `<span class="fw-bold">@lang('Conversion Rate')</span> <span><span  class="fw-bold">1 {{ __(gs('cur_text')) }} = <span class="rate">${rate.toFixed(4)}</span>  <span class="method_currency">${resource.currency}</span></span></span>`;
                    $('.rate-element').html(rateElement)
                    $('.rate-element').removeClass('d-none');
                    $('.in-site-cur').removeClass('d-none');
                    $('.rate-element').addClass('d-flex');
                    $('.in-site-cur').addClass('d-flex');
                } else {
                    $('.rate-element').html('')
                    $('.rate-element').addClass('d-none');
                    $('.in-site-cur').addClass('d-none');
                    $('.rate-element').removeClass('d-flex');
                    $('.in-site-cur').removeClass('d-flex');
                }

                $('.method_currency').text(resource.currency);
                $('input[name=currency]').val(resource.currency);
                $('input[name=amount]').on('input');
            });

        })(jQuery);
    </script>
@endpush

@extends($activeTemplate . 'layouts.master')
@section('content')
    @if (count($plans))
        <div class="row justify-content-between gy-4 align-items-center">
            <div class="col-md-12">
                @include($activeTemplate . 'partials.plan', ['plans' => $plans])
            </div>
        </div>
    @else
        <div class="card custom--card">
            <div class="transection__item justify-content-center p-5 skeleton">
                <div class="empty-thumb text-center">
                    <img src="{{ asset('assets/images/extra_images/empty.png') }}" />
                    <p class="fs-14">@lang('No plan found')</p>
                </div>
            </div>
        </div>
    @endif
@endsection

@push('script')
    <script>
        "use strict";
        (function($) {

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

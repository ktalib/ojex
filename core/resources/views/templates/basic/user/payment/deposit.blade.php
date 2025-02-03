@extends($activeTemplate . 'layouts.master')
@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xxl-9">
                <form action="{{ route('user.plan.save') }}" method="post" class="deposit-form">
                    @csrf
                    <input type="hidden" name="currency">
                    <input type="hidden" name="plan_id" value="{{ $planId }}">
                    <div class="gateway-card">
                        <div class="row justify-content-center gy-sm-4 gy-3">
                            <div class="col-12">
                                <h5 class="payment-card-title">@lang('Payment')</h5>
                            </div>
                            <div class="col-lg-6">
                                <div class="payment-system-list is-scrollable gateway-option-list">
                                    <label for="main-balance" class="payment-item gateway-option">
                                        <div class="payment-item__info">
                                            <span class="payment-item__check"></span>
                                            <span class="payment-item__name">@lang('Main Balance') ({{ showAmount(auth()->user()->balance) }})</span>
                                        </div>
                                        <div class="user-info__icon">
                                            <i class="las la-user"></i>
                                        </div>
                                        <input type="radio" hidden class="payment-item__radio gateway-input" id="main-balance" name="gateway" value="main" @checked(old('gateway'))>
                                    </label>
                                    @foreach ($gatewayCurrency as $data)
                                        <label for="{{ titleToKey($data->name) }}" class="payment-item @if ($loop->index > 3) d-none @endif gateway-option">
                                            <div class="payment-item__info">
                                                <span class="payment-item__check"></span>
                                                <span class="payment-item__name">{{ __($data->name) }}</span>
                                            </div>
                                            <div class="payment-item__thumb">
                                                <img class="payment-item__thumb-img" src="{{ getImage(getFilePath('gateway') . '/' . $data->method->image) }}" alt="@lang('payment-thumb')">
                                            </div>
                                            <input class="payment-item__radio gateway-input" id="{{ titleToKey($data->name) }}" hidden data-gateway='@json($data)' type="radio" name="gateway" value="{{ $data->method_code }}" @checked(old('gateway', $loop->first) == $data->method_code) data-min-amount="{{ showAmount($data->min_amount,2) }}" data-max-amount="{{ showAmount($data->max_amount,2) }}">
                                        </label>
                                    @endforeach
                                    @if ($gatewayCurrency->count() > 3)
                                        <button type="button" class="payment-item__btn more-gateway-option">
                                            <p class="payment-item__btn-text">@lang('Show All Payment Options')</p>
                                            <span class="payment-item__btn__icon"><i class="fas fa-chevron-down"></i></span>
                                        </button>
                                    @endif
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="payment-system-list p-3">
                                    <div class="deposit-info">
                                        <div class="deposit-info__title">
                                            <p class="text mb-0">@lang('Amount')</p>
                                        </div>
                                        <div class="deposit-info__input">
                                            <div class="deposit-info__input-group input-group">
                                                <span class="deposit-info__input-group-text">{{ gs('cur_sym') }}</span>
                                                <input type="text" class="form-control form--control amount" name="amount"  value="{{ getAmount(old('amount', $planPrice)) }}" autocomplete="off" readonly>
                                            </div>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="deposit-info paymentInfo">
                                        <div class="deposit-info__title">
                                            <p class="text has-icon"> @lang('Limit')
                                                <span></span>
                                            </p>
                                        </div>
                                        <div class="deposit-info__input">
                                            <p class="text"><span class="gateway-limit">@lang('0.00')</span>
                                            </p>
                                        </div>
                                    </div>
                                    <div class="deposit-info paymentInfo pb-3">
                                        <div class="deposit-info__title">
                                            <p class="text has-icon">@lang('Processing Charge')
                                                <span data-bs-toggle="tooltip" title="@lang('Processing charge for payment gateways')" class="proccessing-fee-info"><i class="las la-info-circle"></i> </span>
                                            </p>
                                        </div>
                                        <div class="deposit-info__input">
                                            <p class="text"><span class="processing-fee">@lang('0.00')</span>
                                                {{ __(gs('cur_text')) }}
                                            </p>
                                        </div>
                                    </div>

                                    <div class="deposit-info paymentInfo total-amount py-3">
                                        <div class="deposit-info__title">
                                            <p class="text">@lang('Total')</p>
                                        </div>
                                        <div class="deposit-info__input">
                                            <p class="text"><span class="final-amount">@lang('0.00')</span>
                                                {{ __(gs('cur_text')) }}</p>
                                        </div>
                                    </div>

                                    <div class="deposit-info paymentInfo gateway-conversion d-none total-amount pt-2">
                                        <div class="deposit-info__title">
                                            <p class="text">@lang('Conversion')
                                            </p>
                                        </div>
                                        <div class="deposit-info__input">
                                            <p class="text"></p>
                                        </div>
                                    </div>
                                    <div class="deposit-info paymentInfo conversion-currency d-none total-amount pt-2">
                                        <div class="deposit-info__title">
                                            <p class="text">
                                                @lang('In') <span class="gateway-currency"></span>
                                            </p>
                                        </div>
                                        <div class="deposit-info__input">
                                            <p class="text">
                                                <span class="in-currency"></span>
                                            </p>
                                        </div>
                                    </div>
                                    <div class="d-none paymentInfo crypto-message mb-3">
                                        @lang('Conversion with') <span class="gateway-currency"></span> @lang('and final value will Show on next step')
                                    </div>
                                    <button type="submit" class="btn btn--base mt-3 w-100" disabled>
                                        @lang('Confirm Payment')
                                    </button>
                                    <div class="info-text pt-3">
                                        <p class="text">@lang('Ensuring your funds grow safely through our secure deposit process with world-class payment options.')</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script>
        "use strict";
        (function($) {

            var amount = parseFloat($('.amount').val() || 0);
            var gateway, minAmount, maxAmount;


            $('.amount').on('input', function(e) {
                amount = parseFloat($(this).val());
                if (!amount) {
                    amount = 0;
                }
                calculation();
            });

            $('.gateway-input').on('change', function(e) {
                gatewayChange();
            });

            function gatewayChange() {
                let gatewayElement = $('.gateway-input:checked');
                let methodCode = gatewayElement.val();

                if(methodCode == 'main'){
                    $('.paymentInfo').hide();
                    return false;
                }else{
                    $('.paymentInfo').show();
                }


                gateway = gatewayElement.data('gateway');
                minAmount = gatewayElement.data('min-amount');
                maxAmount = gatewayElement.data('max-amount');

                let processingFeeInfo =
                    `${parseFloat(gateway.percent_charge).toFixed(2)}% with ${parseFloat(gateway.fixed_charge).toFixed(2)} {{ __(gs('cur_text')) }} charge for payment gateway processing fees`
                $(".proccessing-fee-info").attr("data-bs-original-title", processingFeeInfo);
                calculation();
            }

            gatewayChange();

            $(".more-gateway-option").on("click", function(e) {
                let paymentList = $(".gateway-option-list");
                paymentList.find(".gateway-option").removeClass("d-none");
                $(this).addClass('d-none');
                paymentList.animate({
                    scrollTop: (paymentList.height() - 60)
                }, 'slow');
            });

            function calculation() {
                if (!gateway) return;
                $(".gateway-limit").text(minAmount + " - " + maxAmount);

                let percentCharge = 0;
                let fixedCharge = 0;
                let totalPercentCharge = 0;

                if (amount) {
                    percentCharge = parseFloat(gateway.percent_charge);
                    fixedCharge = parseFloat(gateway.fixed_charge);
                    totalPercentCharge = parseFloat(amount / 100 * percentCharge);
                }

                let totalCharge = parseFloat(totalPercentCharge + fixedCharge);
                let totalAmount = parseFloat((amount || 0) + totalPercentCharge + fixedCharge);

                $(".final-amount").text(totalAmount.toFixed(2));
                $(".processing-fee").text(totalCharge.toFixed(2));
                $("input[name=currency]").val(gateway.currency);
                $(".gateway-currency").text(gateway.currency);

                if (amount < Number(gateway.min_amount) || amount > Number(gateway.max_amount)) {
                    $(".deposit-form button[type=submit]").attr('disabled', true);
                } else {
                    $(".deposit-form button[type=submit]").removeAttr('disabled');
                }

                if (gateway.currency != "{{ gs('cur_text') }}" && gateway.method.crypto != 1) {
                    $('.deposit-form').addClass('adjust-height')

                    $(".gateway-conversion, .conversion-currency").removeClass('d-none');
                    $(".gateway-conversion").find('.deposit-info__input .text').html(
                        `1 {{ __(gs('cur_text')) }} = <span class="rate">${parseFloat(gateway.currency_data.rate).toFixed(4)}</span>  <span class="method_currency">${gateway.currency}</span>`
                    );
                    $('.in-currency').text(parseFloat(totalAmount / gateway.currency_data.rate).toFixed(gateway.method.crypto == 1 ? 8 : 2))
                } else {
                    $(".gateway-conversion, .conversion-currency").addClass('d-none');
                    $('.deposit-form').removeClass('adjust-height')
                }

                if (gateway.method.crypto == 1) {
                    $('.crypto-message').removeClass('d-none');
                } else {
                    $('.crypto-message').addClass('d-none');
                }
            }

            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
            var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl)
            })
            $('.gateway-input').change();
        })(jQuery);
    </script>
@endpush

@extends($activeTemplate . 'layouts.master')
@section('content')
    @php
        $decimal = $currency->type == Status::CRYPTO_CURRENCY ? 6 : 2;
        $walletBalance = showAmount($wallet->balance, $decimal, currencyFormat: false);
        $transferCharge = getAmount(gs('other_user_transfer_charge'));
        $transferChargeForOtherWallet = getAmount(gs('other_wallet_transfer_charge'));
    @endphp

    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
        <h4 class="mb-0">{{ __($pageTitle) }}</h4>
        <a href="{{ route('user.wallet.list') }}" class="btn btn--base btn--sm outline">
            <i class="la la-undo"></i> @lang('Back')
        </a>
    </div>

    <div class="row justify-content-center gy-4 mb-4">
        <div class="col-xxl-3 col-sm-6">
            <div class="dashboard-card dashboard-card--compact">
                <div class="d-flex justify-content-between align-items-center">
                    <span class="dashboard-card__icon text--base">
                        <i class="las la-spinner"></i>
                    </span>
                    <div class="dashboard-card__content">
                        <a href="{{ route('user.order.open') }}?currency={{ $currency->symbol }}" class="dashboard-card__coin-name mb-0 ">
                            @lang('Open Order')
                        </a>
                        <h6 class="dashboard-card__coin-title"> {{ getAmount(@$widget['open_order']) }} </h6>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xxl-3 col-sm-6">
            <div class="dashboard-card dashboard-card--compact">
                <div class="d-flex justify-content-between align-items-center">
                    <span class="dashboard-card__icon text--base">
                        <i class="las la-check-circle"></i>
                    </span>
                    <div class="dashboard-card__content">
                        <a href="{{ route('user.order.completed') }}?currency={{ $currency->symbol }}" class="dashboard-card__coin-name mb-0">
                            @lang('Completed Order')
                        </a>
                        <h6 class="dashboard-card__coin-title"> {{ getAmount(@$widget['completed_order']) }}
                        </h6>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xxl-3 col-sm-6">
            <div class="dashboard-card dashboard-card--compact">
                <div class="d-flex justify-content-between align-items-center">
                    <span class="dashboard-card__icon text--base fs-50 icon-order"></span>
                    <div class="dashboard-card__content">
                        <a href="{{ route('user.order.history') }}?search={{ @$currency->symbol }}" class="dashboard-card__coin-name mb-0">
                            @lang('Total Order')
                        </a>
                        <h6 class="dashboard-card__coin-title">
                            {{ getAmount($widget['total_order']) }}
                        </h6>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xxl-3 col-sm-6">
            <div class="dashboard-card dashboard-card--compact">
                <div class="d-flex justify-content-between align-items-center">
                    <span class="dashboard-card__icon text--base">
                        <span class="icon-trade fs-50"></span>
                    </span>
                    <div class="dashboard-card__content">
                        <a href="{{ route('user.trade.history') }}" class="dashboard-card__coin-name mb-0">@lang('Total Trade') </a>
                        <h6 class="dashboard-card__coin-title"> {{ getAmount(@$widget['total_trade']) }} </h6>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row justify-content-center gy-4 mb-4">
        <div class="col-xxl-4">
            <div class="dashboard-card dashboard-card--compact">
                <div class="wallet-currency text-center mb-3">
                    <img src="{{ @$wallet->currency->image_url }}">
                    <div class="">
                        <p class="mb-0 fs-16">{{ __(@$wallet->currency->name) }}</p>
                        <p class="mt-0 fs-12">{{ __(@$wallet->currency->symbol) }}</p>
                    </div>
                </div>
                <div class="wallet-ballance p-3 mb-3">
                    <p class="mb-0 fs-16">
                        {{ __(@$wallet->currency->sign) }}{{ showAmount($wallet->balance, $decimal, currencyFormat: false) }}
                    </p>
                    <p class="mt-0 fs-12">@lang('Available Balance')</p>
                </div>
                <div class="d-flex flex-wrap gap-2 mb-3">
                    <div class="flex-fill wallet-ballance p-3 mt-3">
                        <p class="mb-0 fs-16">
                            {{ __(@$wallet->currency->sign) }}{{ showAmount($wallet->in_order, $decimal, currencyFormat: false) }}
                        </p>
                        <p class="mt-0 fs-12">@lang('In Order')</p>
                    </div>
                    <div class="flex-fill wallet-ballance p-3 mt-3 ">
                        <p class="mb-0 fs-16">
                            {{ __(@$wallet->currency->sign) }}{{ showAmount($wallet->total_balance, $decimal, currencyFormat: false) }}
                        </p>
                        <p class="mt-0 fs-12">@lang('Total Balance')</p>
                    </div>
                </div>
                <button class="btn btn--base w-100 convertBtn">@lang('Convert')</button>
            </div>
        </div>
        <div class="col-xxl-8">
            <div class="dashboard-card">
                <h6 class="card-title">@lang('Transaction History')</h6>
                <table class="table {{ $transactions->count() ? 'table--responsive--md' : 'table--empty' }}">
                    <thead>
                        <tr>
                            <th>@lang('Transacted')</th>
                            <th>@lang('Trx')</th>
                            <th>@lang('Amount')</th>
                            <th>@lang('Post Balance')</th>
                            <th>@lang('Detail')</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($transactions as $trx)
                            <tr>
                                <td>
                                    <div class="text-wrap">
                                        {{ showDateTime($trx->created_at) }}<br>{{ diffForHumans($trx->created_at) }}
                                    </div>
                                </td>
                                <td>
                                    <strong>{{ $trx->trx }}</strong>
                                </td>
                                <td class="budget">
                                    <span class="text-wrap fw-bold @if ($trx->trx_type == '+') text--success @else text--danger @endif">
                                        {{ $trx->trx_type }}
                                        {{ showAmount($trx->amount, $decimal, currencyFormat: false) }}
                                        {{ __($trx->wallet->currency->symbol) }}
                                    </span>
                                </td>
                                <td class="budget"> 
                                    <div class="text-wrap">
                                        {{ showAmount($trx->post_balance, $decimal, currencyFormat: false) }}
                                    {{ __($trx->wallet->currency->symbol) }}
                                    </div>
                                </td>
                                <td><div class="text-wrap">{{ __($trx->details) }}</div></td>
                            </tr>
                        @empty
                            @php echo userTableEmptyMessage('transaction') @endphp
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($transactions->hasPages())
                {{ paginateLinks($transactions) }}
            @endif
        </div>
    </div>

    <div class="modal fade custom--modal" id="convertModal" role="dialog" tabindex="-1">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">@lang('Currency Convert') - (<span class="small">@lang('Reaming'): {{ @$user->planHistory->rem_conversion }}</span>)</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <i class="las la-times"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('user.wallet.convert') }}" method="post">
                        @csrf
                        <input type="hidden" name="currency_id" value="{{ $currency->id }}">
                        <div class="form-group">
                            <label class="form--label">@lang('Amount')</label>
                            <div class="input-group">
                                <input class="form-control form--control" type="number" name="amount" step="any">
                                <span class="input-group-text bg--gradient border-0 text-white cursor-pointer maxBtn" data-max_amount="{{ getAmount($wallet->balance) }}">@lang('Max')</span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="form--label">@lang('Convert to')</label>
                            <select class="form--control form--select" name="convert_currency_id" required>
                                <option value="" selected disabled>@lang('Select Currency')</option>
                                @foreach ($currencies as $cur)
                                    <option value="{{ $cur->id }}" data-currency="{{ $cur }}">
                                        {{ __($cur->name) }} - {{ $cur->symbol }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group preview-details d-none">
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item d-flex justify-content-between">
                                    <span>1 {{ $currency->symbol }}</span>
                                    <span><span class="rate fw-bold"></span> <span class="convertCurrency"></span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between">
                                    <span>@lang('Charge')</span>
                                    <span><span class="charge fw-bold"></span> <span class="convertCurrency"></span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between">
                                    <span>@lang('You will get')</span>
                                    <span><span class="totalAmount fw-bold"></span> <span class="convertCurrency"></span>
                                </li>
                            </ul>
                        </div>
                        <button class="deposit__button btn btn--base w-100" type="submit"> @lang('Submit') </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script>
        "use strict";
        (function($) {

            let modal = $('#convertModal');

            $('.convertBtn').on('click', function() {
                modal.modal('show');
            });

            modal.on('hidden.bs.modal', function() {
                $('.preview-details').addClass('d-none');
                modal.find('form')[0].reset();
            });


            let currency = @json($currency);
            let conversionCharge = parseFloat(`{{ gs('conversion_charge') }}`);
            let amount, convertRate;

            $('[name=amount]').on('change', function() {
                amount = $(this).val();
                calculationConversation();
            });

            $('[name=convert_currency_id]').on('change', function() {
                let convertCurrency = $(this).find(':selected').data('currency');
                convertRate = currency.rate / convertCurrency.rate;
                $('.rate').text(convertRate.toFixed(8));
                $('.convertCurrency').text(convertCurrency.symbol);
                calculationConversation();
            });

            $('.maxBtn').on('click', function() {
                amount = $(this).data('max_amount');
                $('[name=amount]').val(amount);
                calculationConversation();
            });

            function calculationConversation() {
                if (amount && convertRate) {
                    $('.preview-details').removeClass('d-none');
                    let totalAmount = amount * convertRate;
                    let charge = totalAmount * conversionCharge / 100;
                    $('.charge').text(getAmount(charge));
                    $('.totalAmount').text(getAmount(totalAmount - charge));
                } else {
                    $('.preview-details').addClass('d-none');
                }
            }
        })(jQuery);
    </script>
@endpush

@push('style')
    <style>
        .wallet-currency img {
            width: 70px;
            border-radius: 50%;
            object-fit: cover;
        }

        .wallet-ballance {
            background-color: #09171a;
        }
    </style>
@endpush

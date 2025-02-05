@extends($activeTemplate . 'layouts.master2')

@section('content')
<main class="p-2 sm:px-2 flex-1 overflow-auto">
    <div class="grid grid-cols-1 ld:grid-cols-2 gap-12">
        <div class="p-4 bg-black rounded-lg shadow">
            <!-- Header --><div class="container mx-auto p-4">


            <div class="show-filter mb-3 text-end">
                <button type="button" class="btn btn--base showFilterBtn btn--sm"><i class="las la-filter"></i>
                    @lang('Filter')</button>
            </div>
            <div class="card dashboard-card p-2 responsive-filter-card mb-4">
                <div class="card-body">
                    <form>
                        <div class="d-flex flex-wrap gap-4">
                            <div class="flex-grow-1">
                                <label class="form--label">@lang('Transaction Number')</label>
                                <input type="text" name="search" value="{{ request()->search }}" class="form-control form--control">
                            </div>
                            <div class="flex-grow-1">
                                <label class="form--label">@lang('Currency')</label>
                                <div class="select2-wrapper">
                                    <select name="symbol" class="form--select form--control select2">
                                        <option value="">@lang('All')</option>
                                        @foreach ($currencies as $currency)
                                            <option value="{{ $currency->symbol }}" @selected(request()->symbol == $currency->symbol)>
                                                {{ __($currency->symbol) }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="flex-grow-1">
                                <label class="form--label">@lang('Type')</label>
                                <div class="select2-wrapper">
                                    <select name="trx_type" class="form--select form--control select2" data-minimum-results-for-search="-1">
                                        <option value="">@lang('All')</option>
                                        <option value="+" @selected(request()->trx_type == '+')>@lang('Plus')</option>
                                        <option value="-" @selected(request()->trx_type == '-')>@lang('Minus')</option>
                                    </select>
                                </div>
                            </div>
                            <div class="flex-grow-1">
                                <label class="form--label">@lang('Remark')</label>
                                <div class="select2-wrapper">
                                    <select class="form--select form--control select2" name="remark">
                                        <option value="">@lang('Any')</option>
                                        @foreach ($remarks as $remark)
                                            <option value="{{ $remark->remark }}" @selected(request()->remark == $remark->remark)>
                                                {{ __(keyToTitle($remark->remark)) }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="flex-grow-1 align-self-end">
                                <button class="btn btn--base w-100"><i class="las la-filter"></i> @lang('Filter')</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <div class="dashboard-card">
                <table class="table {{ $transactions->count() ? 'table--responsive--md' : 'table--empty' }}">
                    <thead>
                        <tr>
                            <th>@lang('Currency | Wallet')</th>
                            <th>@lang('Transacted')</th>
                            <th>@lang('Trx')</th>
                            <th>@lang('Amount')</th>
                            <th>@lang('Post Balance')</th>
                            <th>@lang('Detail')</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($transactions as $trx)
                            @php
                                $curSymbol = $trx->wallet_id ? @$trx->wallet->currency->symbol : gs('cur_text');
                                $decimal = @$trx->wallet->currency->type == Status::CRYPTO_CURRENCY ? 6 : 4;
                            @endphp
                            <tr>
                                <td>
                                    <div class="text-end text-lg-start">
                                        <span>{{ $curSymbol }}</span>
                                        <br>
                                        @if (!$trx->wallet_id)
                                            <small>@lang('Main Balance') </small>
                                        @else
                                            <small>{{ @$trx->wallet->name }} | {{ __(strToUpper(@$trx->wallet->type_text)) }} </small>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    <div class="text-end  text-lg-center">
                                        {{ showDateTime($trx->created_at) }}<br>{{ diffForHumans($trx->created_at) }}
                                    </div>
                                </td>
                                <td>{{ $trx->trx }}</td>
                                <td>
                                    <span class="fw-bold  @if ($trx->trx_type == '+') text--success @else text--danger @endif">
                                        {{ $trx->trx_type }} {{ showAmount($trx->amount, $decimal, currencyFormat: false) }}
                                        {{ $curSymbol }}
                                    </span>
                                </td>
                                <td> <div class="">{{ showAmount($trx->post_balance, $decimal, currencyFormat: false) }}
                                    {{ $curSymbol }}</div>
                                </td>
                                <td><div class="text-wrap min-w-170">{{ __($trx->details) }}</div></td>
                            </tr>
                        @empty
                            @php echo userTableEmptyMessage('transactions') @endphp
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if ($transactions->hasPages())
                {{ paginateLinks($transactions) }}
            @endif
        </div>
    </div>
</main>
@endsection

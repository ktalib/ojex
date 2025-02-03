@extends($activeTemplate . 'layouts.master')
@section('content')
    <div class="dashboard-card">
        <table class="table {{ $wallets->count() ? 'table--responsive--md' : 'table--empty' }}">
            <thead>
                <tr>
                    <th>@lang('Currency')</th>
                    <th>@lang('Available Balance')</th>
                    <th>@lang('In Order')</th>
                    <th>@lang('Total Balance')</th>
                    <th>@lang('Action')</th>
                </tr>
            </thead>
            <tbody>
                @forelse($wallets as $wallet)
                    @php
                        $decimal = $wallet->currency->type == Status::CRYPTO_CURRENCY ? 6 : 2;
                    @endphp
                    <tr>
                        <td>
                            <div class="customer justify-content-end justify-content-lg-start">
                                <div class="customer__thumb">
                                    <img src="{{ @$wallet->currency->image_url }}">
                                </div>
                                <div class="customer__content text-end text-lg-start">
                                    <span>{{ @$wallet->currency->name }}</span>
                                    <small class="fs-12">{{ @$wallet->currency->symbol }}</small>
                                </div>
                            </div>
                        </td>
                        <td>{{ showAmount(@$wallet->balance, $decimal, currencyFormat: false) }}
                            {{ __(@$wallet->currency->symbol) }}</td>
                        <td>{{ showAmount(@$wallet->in_order, $decimal, currencyFormat: false) }}
                            {{ __(@$wallet->currency->symbol) }}</td>
                        <td>{{ showAmount(@$wallet->total_balance, $decimal, currencyFormat: false) }}
                            {{ __(@$wallet->currency->symbol) }}</td>
                        <td>
                            <a href="{{ route('user.wallet.view', ['currencySymbol' => @$wallet->currency->symbol]) }}"
                                class="btn btn--base btn--sm outline">
                                <i class="las la-eye"></i> @lang('View')
                            </a>
                        </td>
                    </tr>
                @empty
                    @php echo userTableEmptyMessage('wallet') @endphp
                @endforelse
            </tbody>
        </table>
    </div>
    @if ($wallets->hasPages())
        {{ paginateLinks($wallets) }}
    @endif
@endsection

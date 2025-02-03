@extends($activeTemplate . 'layouts.master')
@section('content')
    <div class="row gy-4">
        <div class="col-lg-12">
            <form class="d-flex flex-wrap justify-content-end mb-3 gap-3">
                <div class="select2-wrapper">
                    <select class="form--select form--control submit-form-on-change select2" name="trade_side">
                        <option value="" selected disabled>@lang('Trade Side')</option>
                        <option value="">@lang('All')</option>
                        <option value="{{ Status::BUY_SIDE_TRADE }}" @selected(request()->trade_side == Status::BUY_SIDE_TRADE)>@lang('Buy')</option>
                        <option value="{{ Status::SELL_SIDE_TRADE }}" @selected(request()->trade_side == Status::SELL_SIDE_TRADE)>@lang('Sell')</option>
                    </select>
                </div>

                <div class="input-group w-auto">
                    <input type="text" name="search" class="form-control form--control" value="{{ request()->search }}"
                        placeholder="@lang('Pair,coin,currency...')">
                    <button type="submit" class="input-group-text bg--gradient border-0 text-white">
                        <i class="las la-search"></i>
                    </button>
                </div>
            </form>
        </div>
        <div class="col-lg-12">
            <div class="dashboard-card">
                <table class="table {{ $trades->count() ? 'table--responsive--md' : 'table--empty' }}">
                    <thead>
                        <tr>
                            <th>@lang('Order Date | Pair')</th>
                            <th>@lang('Trade Date')</th>
                            <th>@lang('Trade Side')</th>
                            <th>@lang('Rate')</th>
                            <th>@lang('Amount')</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($trades as $trade)
                            <tr>
                                <td>
                                    <div>
                                        {{ $trade->order->formatted_date }}
                                        <br>
                                        {{ @$trade->order->pair->symbol }}
                                    </div>
                                </td>
                                <td>{{ showDateTime($trade->created_at) }}</td>
                                <td> @php  echo $trade->tradeSideBadge; @endphp </td>
                                <td>
                                    {{ showAmount($trade->rate, 8, currencyFormat: false) }}
                                    {{ @$trade->order->pair->market->currency->symbol }}
                                </td>
                                <td> {{ showAmount($trade->amount, currencyFormat: false) }}
                                    {{ @$trade->order->pair->coin->symbol }}</td>
                            </tr>
                        @empty
                            @php echo userTableEmptyMessage('trade') @endphp
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if ($trades->hasPages())
                {{ paginateLinks($trades) }}
            @endif
        </div>
    </div>
@endsection


@push('style')
    <style>
        .select2-wrapper {
            width: 200px;
        }

        @media (max-width: 575px) {
            .select2-wrapper {
                flex-grow: 1;
            }

            .input-group {
                width: 100% !important;
            }
        }
    </style>
@endpush

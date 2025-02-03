@extends('admin.layouts.app')
@section('panel')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <form action="{{ route('admin.bot.config.save') }}" method="POST">
                    @csrf
                    <div class="card-body">
                        <h6 class="p-2 fw-bold">@lang('Buy Order Config')</h6>
                        <ul class="list-group">
                            <li class="list-group-item d-flex flex-wrap flex-sm-nowrap gap-2 justify-content-between align-items-center">
                                <div>
                                    <p class="fw-bold mb-0">@lang('Max buy order')</p>
                                    <p class="mb-0">
                                        <small>@lang('The maximum number of buy orders that can be placed each time. If set to 5, then 0 to 5 orders will be placed each time.')</small>
                                    </p>
                                </div>
                                <div class="form-group">
                                    <input type="number" step="1" name="max_buy_order" value="{{ $botConfig->max_buy_order }}" class="form-control" required>
                                </div>
                            </li>
                            <li class="list-group-item d-flex flex-wrap flex-sm-nowrap gap-2 justify-content-between align-items-center">
                                <div>
                                    <p class="fw-bold mb-0">@lang('Min Decrease')</p>
                                    <p class="mb-0">
                                        <small>@lang('The price decrease used to calculate the order rate. For example, if the current BTC rate is $70,000 and the minimum decrease is 10%, the order price will be less than $63,000.')</small>
                                    </p>
                                </div>
                                <div class="form-group">
                                    <div class="input-group">
                                        <input type="number" name="buy_min_decrease" step="1" value="{{ $botConfig->buy_min_decrease }}" class="form-control" required>
                                        <span class="input-group-text">%</span>
                                    </div>
                                </div>
                            </li>
                            <li class="list-group-item d-flex flex-wrap flex-sm-nowrap gap-2 justify-content-between align-items-center">
                                <div>
                                    <p class="fw-bold mb-0">@lang('Max Decrease')</p>
                                    <p class="mb-0">
                                        <small>@lang('The maximum price decrease used to calculate the order rate. For example, if the current BTC rate is $70,000 and the maximum decrease is 20%, the order price will be greater than $56,000.')</small>
                                    </p>
                                </div>
                                <div class="form-group">
                                    <div class="input-group">
                                        <input type="number" name="buy_max_decrease" step="1" value="{{ $botConfig->buy_max_decrease }}" class="form-control" required>
                                        <span class="input-group-text">%</span>
                                    </div>
                                </div>
                            </li>

                            <li class="list-group-item d-flex flex-wrap flex-sm-nowrap gap-2 justify-content-between align-items-center">
                                <div>
                                    <p class="fw-bold mb-0">@lang('Order Amount Range')</p>
                                    <p class="mb-0">
                                        <small>@lang('This configures the range for the order amount. For example, if it is set to 10% and the minimum buy amount in the CoinPair is 100, then the order amount will be between 100 and 110.')</small>
                                    </p>
                                </div>
                                <div class="form-group">
                                    <div class="input-group">
                                        <input type="text" name="buy_order_amount_range" value="{{ getAmount($botConfig->buy_order_amount_range) }}" class="form-control" required>
                                        <span class="input-group-text">%</span>
                                    </div>
                                </div>
                            </li>
                            <li class="list-group-item d-flex flex-wrap flex-sm-nowrap gap-2 justify-content-between align-items-center">
                                <div>
                                    <p class="fw-bold mb-0">@lang('Order Matching Chance')</p>
                                    <p class="mb-0">
                                        <small>@lang('This defines the probability of bot orders matching with sell orders. For example, if it is set to 50% and the max buy order value is 10, then 0 to 5 orders will match with sell orders.')</small>
                                    </p>
                                </div>
                                <div class="form-group">
                                    <div class="input-group">
                                        <input type="number" name="buy_matching_chance" step="any" value="{{ getAmount($botConfig->buy_matching_chance) }}" class="form-control" required>
                                        <span class="input-group-text">%</span>
                                    </div>
                                </div>
                            </li>
                            <li class="list-group-item d-flex flex-wrap flex-sm-nowrap gap-2 justify-content-between align-items-center">
                                <div>
                                    <p class="fw-bold mb-0">@lang('Order Matching Price Increase Up To')</p>
                                    <p class="mb-0">
                                        <small>@lang('This determines the range within which orders will be matched. For example, if this value is set to 10% and the current BTC price is $70,000, then orders with rates less then or equal to $77,000 will be matched.')</small>
                                    </p>
                                </div>
                                <div class="form-group">
                                    <div class="input-group">
                                        <input type="number" name="buy_matching_price" step="any" value="{{ getAmount($botConfig->buy_matching_price) }}" class="form-control" required>
                                        <span class="input-group-text">%</span>
                                    </div>
                                </div>
                            </li>
                            <li class="list-group-item d-flex flex-wrap flex-sm-nowrap gap-2 justify-content-between align-items-center">
                                <div>
                                    <p class="fw-bold mb-0">@lang('Matching with Bot')</p>
                                    <p class="mb-0">
                                        <small>@lang('This determines whether the order will match with a bot or not. If set to "yes", the bot will match with another bot order. If set to "no", the bot order will only match with real user orders.')</small>
                                    </p>
                                </div>
                                <div class="form-group">
                                    <select name="buy_matching_with_bot" class="form-control select2" data-minimum-results-for-search="-1" required>
                                        <option value="1">@lang('Yes')</option>
                                        <option value="0" @selected(!$botConfig->buy_matching_with_bot)>@lang('No')</option>
                                    </select>
                                </div>
                            </li>
                            <li class="list-group-item d-flex flex-wrap flex-sm-nowrap gap-2 justify-content-between align-items-center">
                                <div>
                                    <p class="fw-bold mb-0">@lang('Order Remains')</p>
                                    <p class="mb-0">
                                        <small>@lang('This defines how many hours the bot order will stay in the system. After this period, the bot order will be deleted.')</small>
                                    </p>
                                </div>
                                <div class="form-group">
                                    <div class="input-group">
                                        <input type="number" step="1" min="0" name="buy_order_remain_hours" value="{{ $botConfig->buy_order_remain_hours }}" class="form-control" required>
                                        <span class="input-group-text">@lang('Hours')</span>
                                    </div>
                                </div>
                            </li>
                            
                                                        
                        </ul>

                        <h6 class="p-2 mt-3 fw-bold">@lang('Sell Order Config')</h6>
                        <ul class="list-group">
                            <li class="list-group-item d-flex flex-wrap flex-sm-nowrap gap-2 justify-content-between align-items-center">
                                <div>
                                    <p class="fw-bold mb-0">@lang('Max Sell Order')</p>
                                    <p class="mb-0">
                                        <small>@lang('The maximum number of sell orders that can be placed each time. If set to 5, then 0 to 5 orders will be placed each time.')</small>
                                    </p>
                                </div>
                                <div class="form-group">
                                    <input type="number" step="1" name="max_sell_order" value="{{ $botConfig->max_sell_order }}" class="form-control" required>
                                </div>
                            </li>
                            <li class="list-group-item d-flex flex-wrap flex-sm-nowrap gap-2 justify-content-between align-items-center">
                                <div>
                                    <p class="fw-bold mb-0">@lang('Min Increase')</p>
                                    <p class="mb-0">
                                        <small>@lang('The price increase used to calculate the order rate. For example, if the current BTC rate is $70,000 and the minimum increase is 10%, the order price will be greater than $77,000.')</small>
                                    </p>
                                </div>
                                <div class="form-group">
                                    <div class="input-group">
                                        <input type="number" name="sell_min_increase" step="1" value="{{ $botConfig->sell_min_increase }}" class="form-control" required>
                                        <span class="input-group-text">%</span>
                                    </div>
                                </div>
                            </li>
                            <li class="list-group-item d-flex flex-wrap flex-sm-nowrap gap-2 justify-content-between align-items-center">
                                <div>
                                    <p class="fw-bold mb-0">@lang('Max Increase')</p>
                                    <p class="mb-0">
                                        <small>@lang('The maximum price increase used to calculate the order rate. For example, if the current BTC rate is $70,000 and the maximum increase is 20%, the order price will be less than $84,000.')</small>
                                    </p>
                                </div>
                                <div class="form-group">
                                    <div class="input-group">
                                        <input type="number" name="sell_max_increase" step="1" value="{{ $botConfig->sell_max_increase }}" class="form-control" required>
                                        <span class="input-group-text">%</span>
                                    </div>
                                </div>
                            </li>
                            <li class="list-group-item d-flex flex-wrap flex-sm-nowrap gap-2 justify-content-between align-items-center">
                                <div>
                                    <p class="fw-bold mb-0">@lang('Order Amount Range')</p>
                                    <p class="mb-0">
                                        <small>@lang('This configures the range for the order amount. For example, if it is set to 10% and the minimum buy amount in the CoinPair is 100, then the order amount will be between 100 and 110.')</small>
                                    </p>
                                </div>
                                <div class="form-group">
                                    <div class="input-group">
                                        <input type="text" name="sell_order_amount_range" value="{{ getAmount($botConfig->sell_order_amount_range) }}" class="form-control" required>
                                        <span class="input-group-text">%</span>
                                    </div>
                                </div>
                            </li>
                            <li class="list-group-item d-flex flex-wrap flex-sm-nowrap gap-2 justify-content-between align-items-center">
                                <div>
                                    <p class="fw-bold mb-0">@lang('Order Matching Chance')</p>
                                    <p class="mb-0">
                                        <small>@lang('This defines the probability of bot orders matching with buy orders. For example, if it is set to 50% and the max sell order value is 10, then 0 to 5 orders will match with buy orders.')</small>
                                    </p>
                                </div>
                                <div class="form-group">
                                    <div class="input-group">
                                        <input type="number" name="sell_matching_chance" step="any" value="{{ getAmount($botConfig->sell_matching_chance) }}" class="form-control" required>
                                        <span class="input-group-text">%</span>
                                    </div>
                                </div>
                            </li>
                            <li class="list-group-item d-flex flex-wrap flex-sm-nowrap gap-2 justify-content-between align-items-center">
                                <div>
                                    <p class="fw-bold mb-0">@lang('Order Matching Price Decrease Up To')</p>
                                    <p class="mb-0">
                                        <small>@lang('This determines the range within which orders will be matched. For example, if this value is set to 10% and the current BTC price is $70,000, then orders with rates above or equal to $63,000 will be matched.')</small>
                                    </p>
                                </div>
                                <div class="form-group">
                                    <div class="input-group">
                                        <input type="number" name="sell_matching_price" step="any" value="{{ getAmount($botConfig->sell_matching_price) }}" class="form-control" required>
                                        <span class="input-group-text">%</span>
                                    </div>
                                </div>
                            </li>
                            <li class="list-group-item d-flex flex-wrap flex-sm-nowrap gap-2 justify-content-between align-items-center">
                                <div>
                                    <p class="fw-bold mb-0">@lang('Matching with Bot')</p>
                                    <p class="mb-0">
                                        <small>@lang('This determines whether the order will match with a bot or not. If set to "yes", the bot will match with another bot order. If set to "no", the bot order will only match with real user orders.')</small>
                                    </p>
                                </div>
                                <div class="form-group">
                                    <select name="sell_matching_with_bot" class="form-control select2" data-minimum-results-for-search="-1" required>
                                        <option value="1">@lang('Yes')</option>
                                        <option value="0" @selected(!$botConfig->sell_matching_with_bot)>@lang('No')</option>
                                    </select>
                                </div>
                            </li>
                            <li class="list-group-item d-flex flex-wrap flex-sm-nowrap gap-2 justify-content-between align-items-center">
                                <div>
                                    <p class="fw-bold mb-0">@lang('Order Remains')</p>
                                    <p class="mb-0">
                                        <small>@lang('This defines how many hours the bot order will stay in the system. After this period, the bot order will be deleted.')</small>
                                    </p>
                                </div>
                                <div class="form-group">
                                    <div class="input-group">
                                        <input type="number" step="1" min="0" name="sell_order_remain_hours" value="{{ $botConfig->sell_order_remain_hours }}" class="form-control" required>
                                        <span class="input-group-text">@lang('Hours')</span>
                                    </div>
                                </div>
                            </li>
                            
                        </ul>

                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn--primary w-100 h-45">@lang('Submit')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('style')
    <style>
        .form-group {
            width: 160px;
            margin-bottom: 0;
            flex-shrink: 0
        }

        .form-control{
            background-color: white !important;
        }        

        .list-group-item:hover {
            background-color: #F7F7F7
        }
    </style>
@endpush

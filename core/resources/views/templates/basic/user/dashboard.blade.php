@extends($activeTemplate . 'layouts.master2')
@php
        $kyc = getContent('kyc.content', true);
    @endphp

<style>
     
    .tabs-container {
      width: 100%;
      max-width: 400px;
      background: #ffffff;
      border-radius: 8px;
      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    .tabs-header {
      display: flex;
      border-bottom: 2px solid #e0e0e0;
    }

    .tab-button {
      flex: 1;
      padding: 10px 15px;
      text-align: center;
      cursor: pointer;
      font-weight: bold;
      color: #555;
      border: none;
      outline: none;
      background: none;
      transition: color 0.3s, background-color 0.3s;
    }

    .tab-button.active {
      color: #ffffff;
      background: #26292c;
    }

    .tabs-content {
      padding: 20px;
    }

    .tab-content {
      display: none;
    }

    .tab-content.active {
      display: block;
    }
  </style>
@section('content')
<main class="p-2 sm:px-2 flex-1 overflow-auto">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-1">
        <!-- Example cards -->



        <div class="p-4 bg-black rounded-lg shadow">

            <div>


                <div class="grid justify-items-stretch">
                    <div>
                   
                        
                     
                        <h3 class="text-sm text-gray-400">Total Balance</h3>
                        <p class="text-2xl font-semibold">{{ showAmount(auth()->user()->balance) }}
                        </p>
                    </div>


                </div>

                <div class="grid justify-items-end">
                    <div>
                        <button class="p-2 hover:bg-gray-800 rounded-lg tab-button active" data-tab="tab1" >
                            <i class="ri-file-list-line text-gray-400"></i>
                        </button>
                        <button class="p-2 hover:bg-gray-800 rounded-lg tab-button" data-tab="tab2">
                            <i class="ri-smartphone-line text-gray-400"></i>
                        </button>
                    </div>
                </div>
 <div id="tab1" class="tab-content active">
    <p class="text-sm text-gray-400">Recent Deposits</p>
                @foreach($Topcurrencies as $currency)
                    @php
                        $symbollowcase = strtolower($currency->currency);
                        $apiUrl = "https://min-api.cryptocompare.com/data/price?fsym={$currency->currency}&tsyms=USD";
                        $response = file_get_contents($apiUrl);
                        $data = json_decode($response, true);
                        $rate = $data['USD'] ?? 0;
                        $amount_usd = $currency->amount * $rate;
                    @endphp
                     
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-[#F7931A]/10 flex items-center justify-center">
                                <img src="https://raw.githubusercontent.com/spothq/cryptocurrency-icons/refs/heads/master/svg/color/{{ $symbollowcase }}.svg" class="w-6 h-6">
                            </div>
                            <div>
                                <div class="text-white">{{ $currency->currency }}</div>
                                
                            </div>
                        </div>
                        <div class="text-right">
                            <div class="text-white">${{ number_format($amount_usd, 2) }}</div>
                            <div class="text-sm text-gray-500">{{ $currency->amount }} {{ $currency->currency }}</div>
                        </div>
                    </div>
                @endforeach
</div>
           
<div  id="tab2" class="tab-content">
    <div  >

        <div class="flex justify-between items-center py-2 border-b border-gray-700">
          <div class="flex items
            -center">
                <li  class="ri-currency-line text-gray-400 mr-2"></li>
                <span class="text-sm text-gray-300">Total Deposits</span>
            
            </div>
            <p class="text-sm text-gray-300">{{ showAmount(auth()->user()->balance) }}</p>
            <a href="{{ route('crypto.deposit.index') }}" class="bg-gray-700 px-3 py-1 text-sm rounded-md hover:bg-gray-600">Deposit</a>
       
        </div>
         

 
        <div class="flex justify-between items-center py-2 border-b border-gray-700">
          <div class="flex items-center">
            <li  class="ri-currency-line text-gray-400 mr-2"></li>
            <span class="text-sm text-gray-300">Total Withdrawals</span>
          </div>
          <div class="flex items-center gap-2">
            <p class="text-sm text-gray-300">{{ showAmount($totalWithdraw) }}</p>
            <a  href="{{ route('user.withdraw') }}" class="bg-gray-700 px-3 py-1 text-sm rounded-md hover:bg-gray-600">Withdraw</a>
          </div>
        </div>
  
        <div class="flex justify-between items-center py-2 border-b border-gray-700">
          <div class="flex items-center">
            <li  class="ri-currency-line text-gray-400 mr-2"></li>
            <span class="text-sm text-gray-300">Total Profits</span>
          </div>
          <p class="text-sm text-gray-300">$0.00</p>
        </div>
  
        <div class="flex justify-between items-center py-2">
          <div class="flex items-center">
           <li  class="ri-shield-check-line text-gray-400 mr-2"></li>
            <span class="text-sm text-gray-300">Verification</span>
          </div>
        @if (auth()->user()->kv == Status::KYC_UNVERIFIED && auth()->user()->kyc_rejection_reason)
            <div class="mb-4 p-4 bg-gradient-to-r from-red-500 to-red-700 text-white rounded-lg" role="alert">
                <div class="flex justify-between items-center">
                    <h4 class="font-bold">@lang('KYC Documents Rejected')</h4>
                </div>
                <hr class="my-2 border-gray-200">
                <p class="mb-0">
                    {{ __(@$kyc->data_values->reject) }} 
                    <a href="javascript::void(0)" class="text-blue-300 underline" data-bs-toggle="modal" data-bs-target="#kycRejectionReason">@lang('Click here')</a> 
                    @lang('to show the reason'). 
                    <a href="{{ route('user.kyc.form') }}" class="text-blue-300 underline">@lang('Click Here')</a> 
                    @lang('to Re-submit Documents'). 
                    <a class="text-blue-300 underline" href="{{ route('user.kyc.data') }}">@lang('See KYC Data')</a>
                </p>
            </div>
        @elseif(auth()->user()->kv == Status::KYC_UNVERIFIED)
            <div class="mb-4 p-4 bg-gradient-to-r from-yellow-500 to-yellow-700 text-white rounded-lg" role="alert">
                <h4 class="font-bold">@lang('KYC Verification Required')</h4>
                <hr class="my-2 border-gray-200">
                <p class="mb-0">
                    {{ __(@$kyc->data_values->required) }} 
                    <a class="text-blue-300 underline" href="{{ route('user.kyc.form') }}">@lang('Click Here to Submit Documents')</a>
                </p>
            </div>
        @elseif(auth()->user()->kv == Status::KYC_PENDING)
            <div class="mb-4 p-4 bg-gradient-to-r from-blue-500 to-blue-700 text-white rounded-lg" role="alert">
                <h4 class="font-bold">@lang('KYC Verification Pending')</h4>
                <hr class="my-2 border-gray-200">
                <p class="mb-0">
                    {{ __(@$kyc->data_values->pending) }} 
                    <a class="text-blue-300 underline" href="{{ route('user.kyc.data') }}">@lang('See KYC Data')</a>
                </p>
            </div>
        @endif
  
        </div>
      </div>
  
      
</div>

 
            </div>
        </div>


        <div class="p-4 bg-black rounded-lg shadow">
            <div>
                <!-- Categories Card -->
                <div class="rounded-lg border border-gray-800 bg-gray-950 p-4">
                    <h3 class="text-sm text-gray-400 mb-2">Categories</h3>
                    @php
                        $totalTradeAmount = $userAssets->sum('amount');
                        $cryptoTrades = $userAssets->where('trade_type', 'Crypto')->sum('amount');
                        $stockTrades = $userAssets->where('trade_type', 'Stocks')->sum('amount');
                        $forexTrades = $userAssets->where('trade_type', 'Forex')->sum('amount');
                        
                        $cryptoPercent = $totalTradeAmount > 0 ? ($cryptoTrades / $totalTradeAmount) * 100 : 0;
                        $stockPercent = $totalTradeAmount > 0 ? ($stockTrades / $totalTradeAmount) * 100 : 0;
                        $forexPercent = $totalTradeAmount > 0 ? ($forexTrades / $totalTradeAmount) * 100 : 0;
                    @endphp

                    @if($totalTradeAmount > 0)
                        <div class="space-y-3">
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-400">Crypto</span>
                                <span class="text-sm text-gray-400">{{ number_format($cryptoPercent, 1) }}%</span>
                            </div>
                            <div class="relative h-2 bg-gray-800 rounded-full overflow-hidden">
                                <div class="absolute top-0 left-0 h-full bg-blue-500 rounded-full" style="width: {{ $cryptoPercent }}%"></div>
                            </div>

                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-400">Stocks</span>
                                <span class="text-sm text-gray-400">{{ number_format($stockPercent, 1) }}%</span>
                            </div>
                            <div class="relative h-2 bg-gray-800 rounded-full overflow-hidden">
                                <div class="absolute top-0 left-0 h-full bg-green-500 rounded-full" style="width: {{ $stockPercent }}%"></div>
                            </div>

                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-400">Forex</span>
                                <span class="text-sm text-gray-400">{{ number_format($forexPercent, 1) }}%</span>
                            </div>
                            <div class="relative h-2 bg-gray-800 rounded-full overflow-hidden">
                                <div class="absolute top-0 left-0 h-full bg-purple-500 rounded-full" style="width: {{ $forexPercent }}%"></div>
                            </div>
                        </div>
                    @else
                        <p class="text-sm">
                            No categories yet.
                            <a href="{{ route('crypto.deposit.index') }}" class="text-blue-400 hover:text-blue-300">Deposit now</a>
                            to see your portfolio breakdown.
                        </p>
                    @endif
                </div>

                <!-- Trading Progress Card -->
                <div>
                    <h3 class="text-sm text-gray-400 mb-2">Trading progress</h3>
                    @php
                        $completedTrades = $userAssets->where('status', 'complete')->count();
                        $totalTrades = $userAssets->count();
                        $progressPercent = $totalTrades > 0 ? ($completedTrades / $totalTrades) * 100 : 0;
                    @endphp
                    <div class="relative h-2 bg-gray-800 rounded-full overflow-hidden">
                        <div class="absolute top-0 left-0 h-full bg-emerald-500 rounded-full" style="width: {{ $progressPercent }}%"></div>
                    </div>
                    <div class="text-right mt-1">
                        <span class="text-sm text-gray-400">{{ number_format($progressPercent, 1) }}%</span>
                    </div>
                </div>

                <!-- Signal Strength Card -->
                <div>
                    <h3 class="text-sm text-gray-400 mb-2">Signal strength</h3>
                    @php
                        // You can modify this value (0-100) to change signal strength
                        $signalStrength = 10; 
                        $barCount = 5;
                        $activeBarCount = ceil(($signalStrength / 100) * $barCount);
                    @endphp
                    <div class="flex gap-1" id="signal-bars">
                        @for ($i = 1; $i <= $barCount; $i++)
                            @if ($i <= $activeBarCount)
                             <div class="signal-bar h-4 w-4 rounded transition-all duration-300" 
                                 style="background-color: {{ $i <= 5 ? '#EF4444' : ($i <= 10 ? '#F59E0B' : '#10B981') }};"></div>
                            @else
                                <div class="signal-bar h-4 w-4 bg-gray-500/20 rounded transition-all duration-300"></div>
                            @endif
                        @endfor
                    </div>
                    <div class="text-right mt-1">
                        <span class="text-sm signal-strength-value {{ $signalStrength > 50 ? 'text-green-500' : 'text-red-500' }}">
                            {{ $signalStrength }}%
                        </span>
                    </div>
                </div>

                <script>
                    // Function to update signal strength
                    function updateSignalStrength(strength) {
                        const bars = document.querySelectorAll('.signal-bar');
                        const activeCount = Math.ceil((strength / 100) * bars.length);
                        
                        bars.forEach((bar, index) => {
                            if (index < activeCount) {
                                // Set color based on position
                                if (index < 3) {
                                    bar.style.backgroundColor = '#EF4444'; // Red
                                } else if (index < 6) {
                                    bar.style.backgroundColor = '#F59E0B'; // Orange 
                                } else if (index < 9) {
                                    bar.style.backgroundColor = '#FBBF24'; // Yellow
                                } else if (index < 12) {
                                    bar.style.backgroundColor = '#34D399'; // Light green
                                } else {
                                    bar.style.backgroundColor = '#10B981'; // Green
                                }
                                bar.classList.remove('bg-gray-500/20');
                            } else {
                                bar.style.backgroundColor = '';
                                bar.classList.add('bg-gray-500/20');
                            }
                        });
                        
                        const strengthDisplay = document.querySelector('.signal-strength-value');
                        strengthDisplay.textContent = `${strength}%`;
                        strengthDisplay.classList.toggle('text-green-500', strength > 50);
                        strengthDisplay.classList.toggle('text-red-500', strength <= 50);
                    }

                    // Example: Update signal strength every 5 seconds with random value
                    setInterval(() => {
                        const newStrength = Math.floor(Math.random() * 100);
                        updateSignalStrength(newStrength);
                    }, 5000);
                </script>
            </div>

        </div>
    </div>

    
    <div class="p-1 space-y-4">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-1">
            <!-- Chart and Trades Section -->
            <div class="lg:col-span-2 space-y-2">
                <!-- Trading Chart -->
                <div class="rounded-lg border border-gray-800 bg-black p-4 w-100">
                    <div>

                        <!-- TradingView Widget BEGIN -->
                        <div class="tradingview-widget-container" style="height:10px;width: 6640px">
                            <div class="tradingview-widget-container__widget"
                                style="height:calc(100% - 32px);width:2345px"></div>
                            <div class="tradingview-widget-copyright"><a href="https://www.tradingview.com/"
                                    rel="noopener nofollow" target="_blank"><span class="blue-text">Track all
                                        markets on TradingView</span></a></div>
                            <script type="text/javascript"
                                src="https://s3.tradingview.com/external-embedding/embed-widget-advanced-chart.js"
                                async>
                                    {

                                        "height": "610",
                                            "symbol": "BINANCE:BTCUSDT",
                                                "interval": "D",
                                                    "timezone": "Etc/UTC",
                                                        "theme": "dark",
                                                            "style": "1",
                                                                "locale": "en",
                                                                    "hide_top_toolbar": true,
                                                                        "allow_symbol_change": true,
                                                                            "calendar": false,
                                                                                "support_host": "https://www.tradingview.com"
                                    }
                                </script>
                        </div>
                        <!-- TradingView Widget END -->
                    </div>
                </div>

            </div>

            <!-- Trading Panel -->
          
            <div class="rounded-lg border border-gray-800 bg-black p-6">
                <!-- filepath: /c:/wamp64/www/ojex/core/resources/views/templates/basic/user/dashboard.blade.php -->
    <form action="{{ route('user.trade.store') }}" method="post">
    @csrf

    <div class="flex gap-4 mb-6">
        <label class="flex items-center gap-2 cursor-pointer">
            <input type="radio" name="action" value="buy" class="form-radio text-emerald-500 hidden" checked>
            <span class="px-4 py-2 bg-emerald-500 text-white rounded-md hover:bg-emerald-600 transition w-full text-center">Buy</span>
        </label>
        <label class="flex items-center gap-2 cursor-pointer">
            <input type="radio" name="action" value="sell" class="form-radio text-red-500 hidden">
            <span class="px-4 py-2 bg-red-500 text-white rounded-md hover:bg-red-600 transition w-full text-center">Sell</span>
        </label>
        <label class="flex items-center gap-2 cursor-pointer">
            <button type="button" onclick="document.getElementById('fiatToCryptoModal').classList.remove('hidden')" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded w-full text-center">
             Fiat to Coin
            </button>
        </label>
        <label class="flex items-center gap-2 cursor-pointer">
            <button type="button" onclick="document.getElementById('cryptoToFiatModal').classList.remove('hidden')" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded w-full text-center">
           Coin to Fiat
            </button>
        </label>
    </div>

    <div class="space-y-4">
        <div>
            <label class="block text-sm text-gray-400 mb-1">Type:</label>
            <select class="w-full bg-gray-900 border border-gray-700 rounded-md p-2" name="trade_type" id="assetType">
                <option value="Crypto">Crypto</option>
                <option value="Stocks">Stocks</option>
                <option value="Forex">Forex</option>
            </select>
        </div>

        <div class="relative w-72">
            <label for="amount" class="block text-sm text-gray-400 mb-1">Amount:</label>
            <div class="flex items-center border border-gray-700 rounded-lg bg-gray-800 text-white px-3 py-2">
                <input id="amount" type="text" name="amount" placeholder="100" class="flex-1 bg-transparent outline-none text-white placeholder-gray-400" />
                <div class="relative">
                    <button id="dropdownButton" type="button" class="flex items-center justify-center space-x-2 text-sm bg-gray-700 px-2 py-1 rounded-lg">
                        <img id="selectedIcon" src="https://raw.githubusercontent.com/spothq/cryptocurrency-icons/refs/heads/master/svg/color/btc.svg" alt="BTC" class="w-4 h-4" />
                        <span id="selectedSymbol">BTC</span>
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4 ml-1">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    <div id="dropdownMenu" class="absolute z-10 hidden mt-2 w-64 bg-gray-900 border border-gray-700 rounded-lg shadow-lg left-0 md:left-auto md:right-0 overflow-auto max-h-40">
                        <div class="p-2">
                            <input type="text" id="assetSearch" placeholder="Search for assets" class="w-full px-2 py-1 text-sm bg-gray-800 text-white rounded-lg border border-gray-700 placeholder-gray-500 focus:ring-1 focus:ring-blue-500 focus:outline-none" />
                        </div>
                        <ul class="max-h-40 overflow-y-auto text-sm" id="assetList">
                            {{-- Assets will be loaded here by javascript --}}
                        </ul>
                    </div>
                </div>
            </div>
            <input type="hidden" name="assets" id="selectedAssetSymbol" />
        </div>

        <div>
            <label class="text-sm text-gray-400 mb-2 block">Current USD Balance: {{ showAmount(auth()->user()->balance) }}</label>
        </div>
        <div>
            <label class="text-sm text-gray-400 mb-2 block">Current Asset Price:</label>
        </div>

        <div class="flex gap-4 mb-6">
            <div class="w-1/2">
                <label class="text-sm text-gray-400 mb-2 block">Stop Loss:</label>
                <input type="number" name="loss" value="6800" class="w-full bg-gray-900 border border-gray-700 rounded-md p-2">
            </div>
            <div class="w-1/2">
                <label class="text-sm text-gray-400 mb-2 block">Take Profit:</label>
                <input type="number" name="profit" value="32100" class="w-full bg-gray-900 border border-gray-700 rounded-md p-2">
            </div>
        </div>

        <div>
            <label class="text-sm text-gray-400 mb-2 block">Duration:</label>
            <select class="w-full bg-gray-900 border border-gray-700 rounded-md p-2" name="duration">
                <option>2 minutes</option>
                <option>5 minutes</option>
                <option>10 minutes</option>
            </select>
        </div>

        <button class="w-full bg-emerald-500 hover:bg-emerald-600 py-3 rounded-md text-white font-medium" type="submit">
            Done
        </button>
    </div>
    </form> 
            </div>
 
        </div>

        
        <!-- Trades Section -->

        <div class="rounded-lg border border-gray-800 bg-black p-4">
            <div>
            <h2 class="text-lg font-semibold mb-4">Trades</h2>
            <div class="space-x-2 mb-4">
            <button class="px-4 py-2 text-sm bg-blue-500 text-white rounded-md" id="openTradesBtn">Open</button>
            <button class="px-4 py-2 text-sm text-gray-400 hover:text-white" id="closedTradesBtn">completed</button>
            </div>

            <!-- Open Trades Table -->
            <div id="openTradesSection">
            @if($userAssets->where('status', 'open')->isEmpty())
            <div class="text-gray-400 text-sm">No open trades yet.</div>
            @else
            <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                <table class="w-full text-sm text-left rtl:text-right text-gray-400">
                <thead class="text-xs text-gray-400 uppercase bg-gray-700">
                <tr>
                    <th scope="col" class="px-6 py-3">Asset</th>
                    <th scope="col" class="px-6 py-3">Type</th>
                    <th scope="col" class="px-6 py-3">Amount</th>
                    <th scope="col" class="px-6 py-3">Loss/Profit</th>
                    <th scope="col" class="px-6 py-3">Action</th>
                    <th scope="col" class="px-6 py-3">Status</th>
                </tr>
                </thead>
                <tbody>
                @foreach($userAssets->where('status', 'open') as $trade)
                <tr class="bg-gray-800 border-b border-gray-700">
                    <td class="px-6 py-4"> 
                        @php
                        $symbollowcase = strtolower($trade->assets);
                        $icon =   $trade->assets;
                        $icon2 = strtolower(substr($trade->assets, 0, 2));
                        $iconSrc = '';

                        if ($trade->trade_type == 'Crypto') {
                            $iconSrc = "https://raw.githubusercontent.com/spothq/cryptocurrency-icons/refs/heads/master/svg/color/{$symbollowcase}.svg";
                        } elseif ($trade->trade_type == 'Stocks') {
                            $iconSrc = "https://cdn.jsdelivr.net/gh/ahmeterenodaci/Nasdaq-Stock-Exchange-including-Symbols-and-Logos/logos/_{$icon}.png"; // Replace with actual stock icon URL
                        } elseif ($trade->trade_type == 'Forex') {
                            $iconSrc = "https://flagcdn.com/36x27/{$icon2}.png"; // Replace with actual forex icon URL
                        }
                    @endphp
                    <img class="w-10 h-10 rounded-full" src="{{ $iconSrc }}" alt="{{ $trade->assets }} image"> 
                    {{ $trade->assets }}</td>
                    <td class="px-6 py-4">{{ $trade->trade_type }}</td>
                    <td class="px-6 py-4">{{ number_format($trade->amount, 2) }}</td>
                    <td class="px-6 py-4">
                        <span class="text-green-500">$ {{ $trade->profit }}</span> <br>
                         <span class="text-red-500">$ {{ $trade->loss }}</span>
                    </td>
                    <td class="px-6 py-4">
                        @if ($trade->action == 'buy')
                            <span class="text-green-500">{{ $trade->action }}</span>
                        @else
                            <span class="text-red-500">{{ $trade->action }}</span>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        
                       @if ($trade->status == 'open')
                          <span class="bg-green-100 text-green-800 text-xs font-medium me-2 px-2.5 py-0.5 rounded-sm dark:bg-green-900 dark:text-green-300">{{ $trade->status }}</span>
                          @else
                            <span class="bg-red-100 text-red-800 text-xs font-medium me-2 px-2.5 py-0.5 rounded-sm dark:bg-red-900 dark:text-red-300">{{ $trade->status }}</span>

                          @endif
                    </td>
                </tr>
                @endforeach
                </tbody>
                </table>
            </div>
            @endif
            </div>

            <!-- Closed Trades Table -->
            <div id="closedTradesSection" class="hidden">
            @if($userAssets->where('status', 'complete')->isEmpty())
            <div class="text-gray-400 text-sm">No closed trades yet.</div>
            @else
            <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                <table class="w-full text-sm text-left rtl:text-right text-gray-400">
                <thead class="text-xs text-gray-400 uppercase bg-gray-700">
                <tr>
                    <th scope="col" class="px-6 py-3">Asset</th>
                    <th scope="col" class="px-6 py-3">Type</th>
                    <th scope="col" class="px-6 py-3">Amount</th>
                    <th scope="col" class="px-6 py-3">Loss/Profit</th>
                    <th scope="col" class="px-6 py-3">Action</th>
                    <th scope="col" class="px-6 py-3">Status</th>
                </tr>
                </thead>
                <tbody>
                @foreach($userAssets->where('status', 'complete') as $trade)
                <tr class="bg-gray-800 border-b border-gray-700">
                    <td class="px-6 py-4">
                        @php
                        $symbollowcase = strtolower($trade->assets);
                        $icon =   $trade->assets;
                        $icon2 = strtolower(substr($trade->assets, 0, 2));
                        $iconSrc = '';

                        if ($trade->trade_type == 'Crypto') {
                            $iconSrc = "https://raw.githubusercontent.com/spothq/cryptocurrency-icons/refs/heads/master/svg/color/{$symbollowcase}.svg";
                        } elseif ($trade->trade_type == 'Stocks') {
                            $iconSrc = "https://cdn.jsdelivr.net/gh/ahmeterenodaci/Nasdaq-Stock-Exchange-including-Symbols-and-Logos/logos/_{$icon}.png"; // Replace with actual stock icon URL
                        } elseif ($trade->trade_type == 'Forex') {
                            $iconSrc = "https://flagcdn.com/36x27/{$icon2}.png"; // Replace with actual forex icon URL
                        }
                    @endphp
                    <img class="w-10 h-10 rounded-full" src="{{ $iconSrc }}" alt="{{ $trade->assets }} image"> 

                        {{ $trade->assets }}
                    
                    </td>
                    <td class="px-6 py-4">{{ $trade->trade_type }}</td>
                    <td class="px-6 py-4">{{ $trade->amount }}</td>
                    <td class="px-6 py-4">
                        <span class="text-green-500">$ {{ $trade->profit }}</span> <br>
                        <span class="text-red-500">$ {{ $trade->loss }}</span>
                    </td>
                    <td class="px-6 py-4">{{ $trade->action }}</td>
                    <td class="px-6 py-4"> 

                        @if ($trade->status == 'open')
                        <span class="bg-green-100 text-green-800 text-xs font-medium me-2 px-2.5 py-0.5 rounded-sm dark:bg-green-900 dark:text-green-300">{{ $trade->status }}</span>
                        @else
                          <span class="bg-red-100 text-red-800 text-xs font-medium me-2 px-2.5 py-0.5 rounded-sm dark:bg-red-900 dark:text-red-300">{{ $trade->status }}</span>

                        @endif
                    </td>
                </tr>
                @endforeach
                </tbody>
                </table>
            </div>
            @endif
            </div>
            </div>
        </div>
        </div>
        <!-- Fiat to Crypto Modal -->
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 hidden" id="fiatToCryptoModal">
            <div class="bg-gray-900 rounded-lg shadow-lg max-w-lg w-full p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-xl font-semibold text-white">Convert Fiat to Crypto</h3>
                    <button onclick="document.getElementById('fiatToCryptoModal').classList.add('hidden')" class="text-gray-400 hover:text-white">
                        ✖
                    </button>
                </div>
        
                <form action="{{ route('user.crypto.deposit.store') }}" method="POST" class="space-y-4">
                    @csrf
                    <input type="hidden" name="type" value="fiat_to_crypto">
        
                    <div>
                        <label for="f2c_fiatAmount" class="text-sm text-gray-300 block">Fiat Amount (USD)</label>
                        <input type="number" id="f2c_fiatAmount" name="fiat_amount" step="0.01" min="0"
                            class="w-full p-3 rounded-md bg-gray-800 text-white focus:ring-2 focus:ring-blue-500"
                            placeholder="Enter amount in USD" required>
                    </div>
        
                    <div>
                        <label for="f2c_cryptoAmount" class="text-sm text-gray-300 block">You will receive</label>
                        <div class="flex items-center gap-2">
                            <input type="text" id="f2c_cryptoAmount" name="crypto_amount" readonly
                                class="flex-1 p-3 rounded-md bg-gray-800 text-white">
                            <span id="f2c_cryptoSymbol" class="text-white"></span>
                        </div>
                    </div>
        
                    <div>
                        <label for="f2c_cryptoSelect" class="text-sm text-gray-300 block">Select Cryptocurrency</label>
                        <div class="relative flex items-center bg-gray-800 rounded-md">
                            <img id="f2c_cryptoIcon" src="" class="w-8 h-8 ml-3" alt="Crypto Icon">
                            <select id="f2c_cryptoSelect" name="currency" class="w-full p-3 pl-12 bg-transparent text-black rounded-md focus:ring-2 focus:ring-blue-500" required>
                                @foreach($currencies as $crypto)
                                    <option value="{{ $crypto->symbol }}" data-icon="https://raw.githubusercontent.com/spothq/cryptocurrency-icons/master/svg/color/{{ strtolower($crypto->symbol) }}.svg">
                                        {{ $crypto->name }} ({{ $crypto->symbol }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
        
                    <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white py-3 rounded-lg transition">
                        Convert to Crypto
                    </button>
                </form>
            </div>
        </div>
        
        <!-- Crypto to Fiat Modal -->
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 hidden" id="cryptoToFiatModal">
            <div class="bg-gray-900 rounded-lg shadow-lg max-w-lg w-full p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-xl font-semibold text-white">Convert Crypto to Fiat</h3>
                    <button onclick="document.getElementById('cryptoToFiatModal').classList.add('hidden')" class="text-gray-400 hover:text-white">
                        ✖
                    </button>
                </div>
        
                <form action="{{ route('user.crypto.deposit.store') }}" method="POST" class="space-y-4">
                    @csrf
                    <input type="hidden" name="type" value="crypto_to_fiat">
        
                    <div>
                        <label for="c2f_cryptoSelect" class="text-sm text-gray-300 block">Select Cryptocurrency</label>
                        <div class="relative flex items-center bg-gray-800 rounded-md">
                            <img id="c2f_cryptoIcon" src="" class="w-8 h-8 ml-3" alt="Crypto Icon">
                            <select id="c2f_cryptoSelect" name="currency" class="w-full p-3 pl-12 bg-transparent text-black rounded-md focus:ring-2 focus:ring-blue-500" required>
                                @foreach($currencies as $crypto)
                                    <option value="{{ $crypto->symbol }}" data-icon="https://raw.githubusercontent.com/spothq/cryptocurrency-icons/master/svg/color/{{ strtolower($crypto->symbol) }}.svg">
                                        {{ $crypto->name }} ({{ $crypto->symbol }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
        
                    <div>
                        <label for="c2f_cryptoAmount" class="text-sm text-gray-300 block">Crypto Amount</label>
                        <input type="number" id="c2f_cryptoAmount" name="crypto_amount" step="0.00000001" min="0"
                            class="w-full p-3 rounded-md bg-gray-800 text-white focus:ring-2 focus:ring-blue-500"
                            placeholder="Enter crypto amount" required>
                    </div>
        
                    <div>
                        <label for="c2f_fiatAmount" class="text-sm text-gray-300 block">You will receive (USD)</label>
                        <input type="text" id="c2f_fiatAmount" name="fiat_amount" 
                            class="w-full p-3 rounded-md bg-gray-800 text-white" readonly>
                    </div>
        
                    <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white py-3 rounded-lg transition">
                        Convert to USD
                    </button>
                </form>
            </div>
        </div>
        
        <script>
            // Initialize Fiat to Crypto conversion
            document.addEventListener('DOMContentLoaded', function() {
                const f2c_fiatAmount = document.getElementById('f2c_fiatAmount');
                const f2c_cryptoAmount = document.getElementById('f2c_cryptoAmount');
                const f2c_cryptoSelect = document.getElementById('f2c_cryptoSelect');
                const f2c_cryptoIcon = document.getElementById('f2c_cryptoIcon');
                const f2c_cryptoSymbol = document.getElementById('f2c_cryptoSymbol');
        
                function updateF2CConversion() {
                    const fiatAmount = parseFloat(f2c_fiatAmount.value) || 0;
                    const cryptoSymbol = f2c_cryptoSelect.value;
        
                    if (fiatAmount <= 0 || !cryptoSymbol) {
                        f2c_cryptoAmount.value = '';
                        return;
                    }
        
                    fetch(`https://min-api.cryptocompare.com/data/price?fsym=${cryptoSymbol}&tsyms=USD`)
                        .then(response => response.json())
                        .then(data => {
                            const price = data.USD;
                            if (price && price > 0) {
                                const cryptoAmount = fiatAmount / price;
                                f2c_cryptoAmount.value = cryptoAmount.toFixed(8);
                                f2c_cryptoSymbol.textContent = cryptoSymbol;
                            }
                        })
                        .catch(error => {
                            console.error('Error fetching crypto price:', error);
                            f2c_cryptoAmount.value = '';
                        });
                }
        
                f2c_fiatAmount.addEventListener('input', updateF2CConversion);
                f2c_cryptoSelect.addEventListener('change', function() {
                    const selectedOption = this.options[this.selectedIndex];
                    f2c_cryptoIcon.src = selectedOption.getAttribute('data-icon');
                    updateF2CConversion();
                });
        
                // Set initial crypto icon
                if (f2c_cryptoSelect.value) {
                    f2c_cryptoIcon.src = f2c_cryptoSelect.options[f2c_cryptoSelect.selectedIndex].getAttribute('data-icon');
                    updateF2CConversion();
                }
            });
        
            // Initialize Crypto to Fiat conversion
            document.addEventListener('DOMContentLoaded', function() {
                const c2f_cryptoAmount = document.getElementById('c2f_cryptoAmount');
                const c2f_fiatAmount = document.getElementById('c2f_fiatAmount');
                const c2f_cryptoSelect = document.getElementById('c2f_cryptoSelect');
                const c2f_cryptoIcon = document.getElementById('c2f_cryptoIcon');
        
                function updateC2FConversion() {
                    const cryptoAmount = parseFloat(c2f_cryptoAmount.value) || 0;
                    const cryptoSymbol = c2f_cryptoSelect.value;
        
                    if (cryptoAmount <= 0 || !cryptoSymbol) {
                        c2f_fiatAmount.value = '';
                        return;
                    }
        
                    fetch(`https://min-api.cryptocompare.com/data/price?fsym=${cryptoSymbol}&tsyms=USD`)
                        .then(response => response.json())
                        .then(data => {
                            const price = data.USD;
                            if (price && price > 0) {
                                const fiatAmount = cryptoAmount * price;
                                c2f_fiatAmount.value = `${fiatAmount.toFixed(2)}`;
                            }
                        })
                        .catch(error => {
                            console.error('Error fetching crypto price:', error);
                            c2f_fiatAmount.value = '';
                        });
                }
        
                c2f_cryptoAmount.addEventListener('input', updateC2FConversion);
                c2f_cryptoSelect.addEventListener('change', function() {
                    const selectedOption = this.options[this.selectedIndex];
                    c2f_cryptoIcon.src = selectedOption.getAttribute('data-icon');
                    updateC2FConversion();
                });
        
                // Set initial crypto icon
                if (c2f_cryptoSelect.value) {
                    c2f_cryptoIcon.src = c2f_cryptoSelect.options[c2f_cryptoSelect.selectedIndex].getAttribute('data-icon');
                }
            });
        </script>
        
        <script>
       document.addEventListener('DOMContentLoaded', function() {
    const dropdownButton = document.getElementById("dropdownButton");
    const dropdownMenu = document.getElementById("dropdownMenu");
    const selectedIcon = document.getElementById("selectedIcon");
    const selectedSymbol = document.getElementById("selectedSymbol");
    const selectedAssetSymbol = document.getElementById("selectedAssetSymbol");
    const assetSearch = document.getElementById("assetSearch");
    const assetType = document.getElementById("assetType");
    const assetList = document.getElementById("assetList");

    let stocksData = [];
    let forexData = [];

    // Fetch stock data
    fetch('https://tradededpro.com/app/assets/global/jsons/stock.json')
        .then(response => response.json())
        .then(data => {
            stocksData = data;
        })
        .catch(error => console.error('Error fetching stock data:', error));

    // Fetch forex data
    fetch('https://tradededpro.com/app/assets/global/jsons/forex.json')
        .then(response => response.json())
        .then(data => {
            forexData = Object.entries(data.usd).map(([symbol, rate]) => ({
                symbol: symbol.toUpperCase(),
                name: symbol.toUpperCase(),
                rate: rate
            }));
        })
        .catch(error => console.error('Error fetching forex data:', error));

    // Function to populate asset list based on selected type
    function populateAssetList(type) {
        assetList.innerHTML = ''; // Clear existing list

        let assets = [];
        let iconBaseUrl = '';

        if (type === 'Crypto') {
            assets = @json($assets);
            iconBaseUrl = 'https://raw.githubusercontent.com/spothq/cryptocurrency-icons/refs/heads/master/svg/color/';
        } else if (type === 'Stocks') {
            assets = stocksData;
        } else if (type === 'Forex') {
            assets = forexData;
        }

        assets.forEach(asset => {
            let iconSrc = '';
            if (type === 'Crypto') {
                const symbollowcase = asset.symbol.toLowerCase();
                iconSrc = iconBaseUrl + symbollowcase + '.svg';
            } else if (type === 'Stocks') {
                iconSrc = asset.logoUrl;
            } else if (type === 'Forex') {
                iconSrc = `https://flagcdn.com/36x27/${asset.symbol.substring(0, 2).toLowerCase()}.png`; // Flag icon
            }

            const listItem = document.createElement('li');
            listItem.classList.add('asset-item', 'flex', 'items-center', 'justify-between', 'px-4', 'py-2', 'hover:bg-gray-700', 'cursor-pointer');
            listItem.setAttribute('data-symbol', asset.symbol);
            listItem.setAttribute('data-name', asset.symbol);
            listItem.setAttribute('data-icon', iconSrc);

            listItem.innerHTML = `
                <div class="flex items-center space-x-2">
                    <img src="${iconSrc}" alt="${asset.symbol}" class="w-4 h-4" onerror="this.onerror=null; this.src='https://cdn-icons-png.flaticon.com/512/0/381.png'">
                    <span>${asset.symbol.toUpperCase()}</span>
                </div>
                <span class="text-gray-400 text-xs">${asset.symbol.toUpperCase()}</span>
            `;
            assetList.appendChild(listItem);

            // Add click listener to asset item
            listItem.addEventListener("click", () => {
                const symbol = listItem.getAttribute("data-symbol");
                const name = listItem.getAttribute("data-name");
                const icon = listItem.getAttribute("data-icon");

                // Update dropdown button
                selectedIcon.src = icon;
                selectedSymbol.textContent = symbol;

                // Set hidden input for form submission
                selectedAssetSymbol.value = symbol;

                // Close dropdown
                dropdownMenu.classList.add("hidden");
            });
        });
    }

    // Initial population of asset list
    populateAssetList(assetType.value);

    // Repopulate asset list on asset type change
    assetType.addEventListener('change', (event) => {
        populateAssetList(event.target.value);
    });

    // Toggle dropdown
    dropdownButton.addEventListener("click", () => {
        dropdownMenu.classList.toggle("hidden");
    });
 
    // Close dropdown when clicking outside
    document.addEventListener('click', function(event) {
        if (!dropdownMenu.contains(event.target) && !dropdownButton.contains(event.target)) {
            dropdownMenu.classList.add("hidden");
        }
    });

    // Search functionality
    assetSearch.addEventListener("input", function(e) {
        const query = e.target.value.toLowerCase();
        document.querySelectorAll(".asset-item").forEach((item) => {
            const text = item.textContent.toLowerCase();
            item.style.display = text.includes(query) ? "flex" : "none";
        });
    });
});

const tabButtons = document.querySelectorAll('.tab-button');
    const tabContents = document.querySelectorAll('.tab-content');

    tabButtons.forEach((button) => {
      button.addEventListener('click', () => {
        // Remove "active" class from all buttons and contents
        tabButtons.forEach(btn => btn.classList.remove('active'));
        tabContents.forEach(content => content.classList.remove('active'));

        // Add "active" class to the clicked button and corresponding content
        button.classList.add('active');
        const tabId = button.getAttribute('data-tab');
        document.getElementById(tabId).classList.add('active');
      });
    });
  </script>
@endsection

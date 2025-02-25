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
    <p class="text-sm text-gray-400">Top Cryptocurrencies</p>
                @foreach($Topcurrencies as $currency)
                    @php
                        $symbollowcase = strtolower($currency->symbol);
                        $apiUrl = "https://min-api.cryptocompare.com/data/price?fsym={$currency->symbol}&tsyms=USD";
                        $response = file_get_contents($apiUrl);
                        $data = json_decode($response, true);
                        $rate = $data['USD'] ?? 0;
                    @endphp
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-[#F7931A]/10 flex items-center justify-center">
                                <img src="https://raw.githubusercontent.com/spothq/cryptocurrency-icons/refs/heads/master/svg/color/{{ $symbollowcase }}.svg" class="w-6 h-6">
                            </div>
                            <div>
                                <div class="text-white">{{ $currency->name }}</div>
                                <div class="text-sm text-gray-500">{{ $currency->symbol }}</div>
                            </div>
                        </div>
                        <div class="text-right">
                            <div class="text-white">${{ number_format($rate, 2) }}</div>
                            <div class="text-sm text-gray-500">{{ number_format($rate, 2) }} {{ $currency->symbol }}</div>
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
                    <p class="text-sm">
                        No categories yet.
                        <a href="#" class="text-blue-400 hover:text-blue-300">Deposit now</a>
                        to see your portfolio breakdown.
                    </p>
                </div>

                <!-- Trading Progress Card -->
                <div>
                    <h3 class="text-sm text-gray-400 mb-2">Trading progress</h3>
                    <div class="relative h-2 bg-gray-800 rounded-full overflow-hidden">
                        <div class="absolute top-0 left-0 h-full w-0 bg-emerald-500 rounded-full"></div>
                    </div>
                    <div class="text-right mt-1">
                        <span class="text-sm text-gray-400">0%</span>
                    </div>
                </div>

                <!-- Signal Strength Card -->
                <div>
                    <h3 class="text-sm text-gray-400 mb-2">Signal strength</h3>
                    <div class="flex gap-1">
                        <!-- 15 red bars -->
                        <div class="h-4 w-4 bg-red-500/20 rounded"></div>
                        <div class="h-4 w-4 bg-red-500/20 rounded"></div>
                        <div class="h-4 w-4 bg-red-500/20 rounded"></div>
                        <div class="h-4 w-4 bg-red-500/20 rounded"></div>
                        <div class="h-4 w-4 bg-red-500/20 rounded"></div>
                        <div class="h-4 w-4 bg-red-500/20 rounded"></div>
                        <div class="h-4 w-4 bg-red-500/20 rounded"></div>
                        <div class="h-4 w-4 bg-red-500/20 rounded"></div>
                        <div class="h-4 w-4 bg-red-500/20 rounded"></div>
                        <div class="h-4 w-4 bg-red-500/20 rounded"></div>
                        <div class="h-4 w-4 bg-red-500/20 rounded"></div>
                        <div class="h-4 w-4 bg-red-500/20 rounded"></div>
                        <div class="h-4 w-4 bg-red-500/20 rounded"></div>
                        <div class="h-4 w-4 bg-red-500/20 rounded"></div>
                        <div class="h-4 w-4 bg-red-500/20 rounded"></div>
                    </div>
                    <div class="text-right mt-1">
                        <span class="text-sm text-red-500">0%</span>
                    </div>
                </div>
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
            <span class="px-4 py-2 bg-emerald-500 text-white rounded-md hover:bg-emerald-600 transition">Buy</span>
        </label>
        <label class="flex items-center gap-2 cursor-pointer">
            <input type="radio" name="action" value="sell" class="form-radio text-red-500 hidden">
            <span class="px-4 py-2 bg-red-500 text-white rounded-md hover:bg-red-600 transition">Sell</span>
        </label>
        <label class="flex items-center gap-2 cursor-pointer">
            <button type="button" onclick="document.getElementById('convertModal').classList.remove('hidden')" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                Convert
              </button>
            {{-- <input type="radio" name="action" value="convert" class="form-radio text-blue-500 hidden">
            <span class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600 transition">Convert <small class="text-red">(soon)</small></span> --}}
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

        <div class="fixed inset-0 z-50 overflow-y-auto hidden" id="convertModal">
            <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity" aria-hidden="true">
                <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
            </div>
        
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        
            <div class="inline-block align-bottom bg-gray-900 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full" role="dialog" aria-modal="true" aria-labelledby="convertModalLabel">
                <div class="bg-gray-900 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <div class="sm:flex sm:items-start">
                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                    <h3 class="text-lg leading-6 font-medium text-white" id="convertModalLabel">
                        Convert Fiat to Crypto
                    </h3>
                    <div class="mt-2">
                        <form action="{{ route('user.crypto.deposit.store') }}" method="POST"   id="depositForm">
                        @csrf
                        
                        <input type="hidden" name="type" value="convert">
                        <div class="mb-4">
                            <label for="fiatAmount" class="block text-sm font-medium text-white">Amount (USD)</label>
                            <input type="number" class="mt-1 block w-full rounded-md border-gray-700 bg-gray-800 text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 p-2" id="fiatAmount" name="amount" required>
                        </div>
                        <div class="mb-4">
                            <label for="cryptoSelect" class="block text-sm font-medium text-white">Select Crypto</label>
                            <select class="mt-1 block w-full rounded-md border-gray-700 bg-gray-800 text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 p-2" id="cryptoSelect" name="currency" required>
                            @foreach($currencies as $crypto)
                                <option value="{{ $crypto->symbol }}" data-icon="https://raw.githubusercontent.com/spothq/cryptocurrency-icons/refs/heads/master/svg/color/{{ strtolower($crypto->symbol) }}.svg">
                                {{ $crypto->name }} ({{ $crypto->symbol }})
                                </option>
                            @endforeach
                            </select>
                        </div>
                        <div class="mb-4">
                            <img id="cryptoIcon" src="" alt="Crypto Icon" class="w-10 h-10">
                        </div>
                        <div class="mb-4">
                            <label for="cryptoAmount" class="block text-sm font-medium text-white">Amount (Crypto)</label>
                            <input type="text" class="mt-1 block w-full rounded-md border-gray-700 bg-gray-800 text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 p-2" id="cryptoAmount" name="crypto_amount" readonly>
                        </div>
                        <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-500 text-base font-medium text-white hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            Convert
                        </button>
                        </form>
                    </div>
                    </div>
                </div>
                </div>
                <div class="bg-gray-900 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                <button type="button" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-700 shadow-sm px-4 py-2 bg-gray-800 text-base font-medium text-white hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 sm:mt-0 sm:ml-3 sm:w-auto" onclick="document.getElementById('convertModal').classList.add('hidden')">
                    Close
                </button>
                </div>
            </div>
            </div>
        </div>
        
        <script>
            document.getElementById('cryptoSelect').addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            const iconUrl = selectedOption.getAttribute('data-icon');
            document.getElementById('cryptoIcon').src = iconUrl;
            updateCryptoAmount();
            });

            document.getElementById('fiatAmount').addEventListener('input', updateCryptoAmount);

            function updateCryptoAmount() {
            const fiatAmount = document.getElementById('fiatAmount').value;
            const cryptoSymbol = document.getElementById('cryptoSelect').value;

            if (fiatAmount && cryptoSymbol) {
                fetch(`https://min-api.cryptocompare.com/data/price?fsym=${cryptoSymbol}&tsyms=USD`)
                .then(response => response.json())
                .then(data => {
                    const price = data.USD;
                    const cryptoAmount = fiatAmount / price;
                    document.getElementById('cryptoAmount').value = cryptoAmount.toFixed(8);
                })
                .catch(error => console.error('Error fetching crypto price:', error));
            }
            }
        </script>

    </main>

     

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

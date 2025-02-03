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
                <form action="{{ route('user.trade.store') }}" method="post">
                    @csrf
                  
                    <div class="flex gap-4 mb-6">
                        <label class="flex items-center gap-2">
                            <input type="radio" name="trade_type[]" value="buy" class="form-radio text-emerald-500">
                            <span class="text-white">Buy</span>
                        </label>
                        <label class="flex items-center gap-2">
                            <input type="radio" name="trade_type[]" value="sell" class="form-radio text-red-500">
                            <span class="text-white">Sell</span>
                        </label>
                        <label class="flex items-center gap-2">
                            <input type="radio" name="trade_type[]" value="convert" class="form-radio text-blue-500">
                            <span class="text-white">Convert</span>
                        </label>
                    </div>
                    
               

                <div class="space-y-4">
                    <div>
                        <label class="text-sm text-gray-400 mb-2 block">Type:</label>
                        <select class="w-full bg-gray-900 border border-gray-700 rounded-md p-2" name="type">
                            <option value="Crypto">Crypto</option>
                            <option value="Stocks">Stocks</option>
                            <option value="Forex">Forex</option>
                        </select>
                    </div>

                    <div class="relative w-72">
                        <label for="amount" class="block text-sm text-gray-400 mb-1">Amount:</label>
                        <div class="flex items-center border border-gray-700 rounded-lg bg-gray-800 text-white px-3 py-2">
                            <input
                                id="amount"
                                type="text"
                                name="amount"
                                placeholder="100"
                                class="flex-1 bg-transparent outline-none text-white placeholder-gray-400"
                            />
                            <div class="relative">
                                <button
                                    id="dropdownButton"
                                    type="button"
                                    class="flex items-center justify-center space-x-2 text-sm bg-gray-700 px-2 py-1 rounded-lg"
                                >
                                    <img id="selectedIcon" src="https://raw.githubusercontent.com/spothq/cryptocurrency-icons/refs/heads/master/svg/color/btc.svg" alt="BTC" class="w-4 h-4" />
                                    <span id="selectedSymbol">BTC</span>
                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke-width="2"
                                        stroke="currentColor"
                                        class="w-4 h-4 ml-1"
                                    >
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                                    </svg>
                                </button>
                                <div
                                    id="dropdownMenu"
                                    class="absolute z-10 hidden mt-2 w-64 bg-gray-900 border border-gray-700 rounded-lg shadow-lg left-0 md:left-auto md:right-0 overflow-auto max-h-40"
                                >
                                    <div class="p-2">
                                        <input
                                            type="text"
                                            id="assetSearch"
                                            placeholder="Search for assets"
                                            class="w-full px-2 py-1 text-sm bg-gray-800 text-white rounded-lg border border-gray-700 placeholder-gray-500 focus:ring-1 focus:ring-blue-500 focus:outline-none"
                                        />
                                    </div>
                                    <ul class="max-h-40 overflow-y-auto text-sm">
                                        @foreach($assets as $asset)
                                        @php
                                            $symbollowcase = strtolower($asset->symbol);
                                        @endphp
                                        <li 
                                            data-symbol="{{ $asset->symbol }}" 
                                            data-name="{{ $asset->name }}"
                                            data-icon="https://raw.githubusercontent.com/spothq/cryptocurrency-icons/refs/heads/master/svg/color/{{ $symbollowcase }}.svg"
                                            class="asset-item flex items-center justify-between px-4 py-2 hover:bg-gray-700 cursor-pointer"
                                        >
                                            <div class="flex items-center space-x-2">                                      
                                                <img src="https://raw.githubusercontent.com/spothq/cryptocurrency-icons/refs/heads/master/svg/color/{{ $symbollowcase }}.svg" alt="{{ $asset->symbol }}" class="w-4 h-4" />
                                                <span>{{ $asset->name }}</span>
                                            </div>
                                            <span class="text-gray-400 text-xs">{{ $asset->symbol }}</span>
                                        </li>
                                        @endforeach
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
                    <label class="text-sm text-gray-400 mb-2 block">Current Asset Price:            $00098</label>
                     
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

                    <button
                        class="w-full bg-emerald-500 hover:bg-emerald-600 py-3 rounded-md text-white font-medium" type="submit">
                        Buy
                    </button>
                </div>
            </div>

            </form>
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
                    <th scope="col" class="px-6 py-3">Price</th>
                    <th scope="col" class="px-6 py-3">Status</th>
                </tr>
                </thead>
                <tbody>
                @foreach($userAssets->where('status', 'open') as $trade)
                <tr class="bg-gray-800 border-b border-gray-700">
                    <td class="px-6 py-4">  @php
                        $symbollowcase = strtolower($trade->assets);

                    @endphp
                    <img class="w-10 h-10 rounded-full" src="https://raw.githubusercontent.com/spothq/cryptocurrency-icons/refs/heads/master/svg/color/{{ $symbollowcase}}.svg" alt="Jese image">

                    {{ $trade->assets }}</td>
                    <td class="px-6 py-4">{{ $trade->type }}</td>
                    <td class="px-6 py-4">{{ $trade->amount }}</td>
                    <td class="px-6 py-4">{{ $trade->price }}</td>
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
                    <th scope="col" class="px-6 py-3">Price</th>
                    <th scope="col" class="px-6 py-3">Status</th>
                </tr>
                </thead>
                <tbody>
                @foreach($userAssets->where('status', 'complete') as $trade)
                <tr class="bg-gray-800 border-b border-gray-700">
                    <td class="px-6 py-4">
                        @php
                            $symbollowcase = strtolower($trade->assets);

                        @endphp
                        <img class="w-10 h-10 rounded-full" src="https://raw.githubusercontent.com/spothq/cryptocurrency-icons/refs/heads/master/svg/color/{{ $symbollowcase}}.svg" alt="Jese image">

                        {{ $trade->assets }}
                    
                    </td>
                    <td class="px-6 py-4">{{ $trade->type }}</td>
                    <td class="px-6 py-4">{{ $trade->amount }}</td>
                    <td class="px-6 py-4">{{ $trade->price }}</td>
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
    </main>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
        const openTradesBtn = document.getElementById('openTradesBtn');
        const closedTradesBtn = document.getElementById('closedTradesBtn');
        const openTradesSection = document.getElementById('openTradesSection');
        const closedTradesSection = document.getElementById('closedTradesSection');

        openTradesBtn.addEventListener('click', () => {
            openTradesSection.classList.remove('hidden');
            closedTradesSection.classList.add('hidden');
            openTradesBtn.classList.add('bg-blue-500', 'text-white');
            closedTradesBtn.classList.remove('bg-blue-500', 'text-white');
            closedTradesBtn.classList.add('text-gray-400');
        });

        closedTradesBtn.addEventListener('click', () => {
            closedTradesSection.classList.remove('hidden');
            openTradesSection.classList.add('hidden');
            closedTradesBtn.classList.add('bg-blue-500', 'text-white');
            openTradesBtn.classList.remove('bg-blue-500', 'text-white');
            openTradesBtn.classList.add('text-gray-400');
        });

        const dropdownButton = document.getElementById("dropdownButton");
        const dropdownMenu = document.getElementById("dropdownMenu");
        const selectedIcon = document.getElementById("selectedIcon");
        const selectedSymbol = document.getElementById("selectedSymbol");
        const selectedAssetSymbol = document.getElementById("selectedAssetSymbol");
        const assetSearch = document.getElementById("assetSearch");

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

// Asset selection
document.querySelectorAll(".asset-item").forEach((item) => {
item.addEventListener("click", () => {
    const symbol = item.getAttribute("data-symbol");
    const name = item.getAttribute("data-name");
    const icon = item.getAttribute("data-icon");

    // Update dropdown button
    selectedIcon.src = icon;
    selectedSymbol.textContent = symbol;
    
    // Set hidden input for form submission
    selectedAssetSymbol.value = symbol;

    // Close dropdown
    dropdownMenu.classList.add("hidden");
});
});

// Search functionality
assetSearch.addEventListener("input", function (e) {
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

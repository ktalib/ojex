@extends($activeTemplate . 'layouts.master2')

@section('content')
<main class="p-2 sm:px-2 flex-1 overflow-auto">
    <div class="grid grid-cols-1 ld:grid-cols-2 gap-12">
        <div class="p-4 bg-black rounded-lg shadow">
            <!-- Header --><div class="container mx-auto p-4">
        <!-- Header -->
    

        <!-- Main Content -->
         

        <!-- Assets List -->
        <div class="mt-8">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-lg">All your assets</h2>
                <div class="flex space-x-4">
                    
                    <div class="dropdown">
                        <select id="marketType" class="bg-gray-800 text-white rounded-lg p-2">
                            <option value="fiat">Fiat Assets</option>
                            <option value="crypto">Crypto Assets</option>
                            <option value="stocks">Stocks Assets</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Assets Table -->
           
                <div class="overflow-x-auto">
                   



                <div id="loadingMessage" class="loading text-white">Loading market data...</div>
                <div id="errorMessage" class="error text-red-500"></div>
                <table id="marketTable" class="min-w-full divide-y divide-gray-700 bg-gray-800 text-white" style="display:none;">
                    <thead class="bg-gray-800">
                        <tr>
                            <th class="py-3 px-4">Asset</th>
                            <th class="py-3 px-4">Type</th>
                            <th class="py-3 px-4">Current Price (USD)</th>
                            <th class="py-3 px-4">In Your Wallet</th>
                            <th class="py-3 px-4">Action</th>
                        </tr>
                    </thead>
                    <tbody id="marketTableBody" class="divide-y divide-gray-700">
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</main>

@push('style')
<style>
    .tooltip {
        position: relative;
    }

    .tooltip:before {
        content: attr(data-tooltip);
        position: absolute;
        bottom: 100%;
        left: 50%;
        transform: translateX(-50%);
        padding: 4px 8px;
        background-color: rgba(0, 0, 0, 0.8);
        color: white;
        border-radius: 4px;
        font-size: 12px;
        white-space: nowrap;
        opacity: 0;
        visibility: hidden;
        transition: opacity 0.2s, visibility 0.2s;
    }

    .tooltip:hover:before {
        opacity: 1;
        visibility: visible;
    }
</style>
@endpush

@push('script')
<script>
    const API_KEY = 'YOUR_API_KEY'; // Replace with actual API key
    const marketTypes = {
        fiat: [
        'AUD', 'CAD', 'CHF', 'CNY', 'EUR', 
        'GBP', 'JPY', 'USD', 'NZD', 'KRW', 
        'INR', 'BRL', 'ZAR', 'RUB', 'SGD',
        'MXN', 'HKD', 'NOK', 'SEK', 'DKK',
        'PLN', 'CZK', 'HUF', 'ILS', 'MYR',
        'PHP', 'THB', 'IDR', 'TRY', 'SAR',
        'AED', 'KWD', 'BHD', 'OMR', 'QAR',
        'EGP', 'NGN', 'GHS', 'KES', 'TZS',
        'UGX', 'ZMW', 'MAD', 'DZD', 'TND',
        'LBP', 'JOD', 'IQD', 'IRR', 'PKR',
        'BDT', 'LKR', 'NPR', 'MMK', 'KHR',
        'LAK', 'VND', 'MNT', 'KZT', 'UZS',
        'TMT', 'AZN', 'GEL', 'AMD', 'BYN',
        'MDL', 'UAH', 'KGS', 'TJS', 'AFN',
        'BND', 'BWP', 'NAD', 'SZL', 'LSL',
        'MZN', 'AOA', 'MWK', 'ZWL', 'ETB',
        'DJF', 'SOS', 'SDG', 'RWF', 'BIF',
        'CDF', 'XAF', 'XOF', 'XPF', 'PGK'
        ],
        crypto: [
        'BTC', 'ETH', 'BNB', 'XRP', 'ADA', 
        'DOGE', 'DOT', 'UNI', 'LINK', 'LTC', 
        'BCH', 'SOL', 'MATIC', 'AVAX', 'SHIB',
        'XLM', 'THETA', 'VET', 'TRX', 'EOS',
        'FIL', 'XTZ', 'AAVE', 'ATOM', 'MKR',
        'COMP', 'SNX', 'YFI', 'SUSHI', 'ZRX',
        'BAT', 'ENJ', 'GRT', 'KSM', 'LRC',
        'MANA', 'REN', 'RUNE', 'ZIL', '1INCH',
        'ANKR', 'BAL', 'BNT', 'CRV', 'FTM',
        'HNT', 'ICX', 'KAVA', 'KNC', 'NANO',
        'NEO', 'OCEAN', 'OMG', 'ONT', 'QNT',
        'RSR', 'STMX', 'STORJ', 'UMA', 'WAVES',
        'XEM', 'ZEN', 'ZEC', 'ALGO', 'CHZ',
        'DGB', 'HBAR', 'HOT', 'IOTA', 'KSM',
        'LUNA', 'NEXO', 'OXT', 'PAXG', 'RLC',
        'SC', 'SXP', 'TFUEL', 'TOMO', 'UOS',
        'VTHO', 'WTC', 'XVG', 'XYO', 'YFI',
        'ZRX', 'AION', 'ARDR', 'ARK', 'BAND'
        ],
        stocks: [
        'AAPL', 'GOOGL', 'MSFT', 'AMZN', 'TSLA', 
        'FB', 'NFLX', 'NVDA', 'INTC', 'AMD', 
        'BABA', 'TSM', 'V', 'JPM', 'DIS',
        'PYPL', 'ADBE', 'CSCO', 'PEP', 'NKE',
        'ORCL', 'CRM', 'ABT', 'ABBV', 'TMO',
        'ACN', 'AVGO', 'COST', 'TXN', 'QCOM',
        'MDT', 'NEE', 'HON', 'UNH', 'LIN',
        'PM', 'UPS', 'RTX', 'LOW', 'INTU',
        'AMGN', 'SBUX', 'IBM', 'MMM', 'GE',
        'CAT', 'CVX', 'BA', 'GS', 'BLK',
        'SPGI', 'PLD', 'AXP', 'MS', 'SCHW',
        'CI', 'ANTM', 'CB', 'DUK', 'SO',
        'D', 'MMC', 'PNC', 'USB', 'TFC',
        'BK', 'STT', 'AIG', 'MET', 'PRU',
        'TRV', 'ALL', 'HIG', 'L', 'CINF',
        'PGR', 'WRB', 'CNA', 'RE', 'RNR',
        'MKL', 'ACGL', 'RLI', 'SIGI', 'THG',
        'ERIE', 'KMPR', 'MCY', 'AFG', 'WRB',
        'CINF', 'PGR', 'WRB', 'CNA', 'RE'
        ]
    };

    const marketTypeDropdown = document.getElementById('marketType');
    const marketTable = document.getElementById('marketTable');
    const marketTableBody = document.getElementById('marketTableBody');
    const loadingMessage = document.getElementById('loadingMessage');
    const errorMessage = document.getElementById('errorMessage');

    async function fetchMarketData(type) {
        loadingMessage.style.display = 'block';
        errorMessage.innerHTML = '';
        marketTable.style.display = 'none';
        marketTableBody.innerHTML = '';

        try {
            const fetchPromises = marketTypes[type].map(async (asset) => {
                if (type === 'fiat') {
                    const response = await fetch(`https://v6.exchangerate-api.com/v6/fede53a207b3780ea8736247/latest/${asset}`);
                    const data = await response.json();
                    return {
                        asset: asset,
                        type: 'Fiat',
                        price: data.conversion_rates['USD'].toFixed(4),
                        inWallet: '0.00'
                    };
                } else if (type === 'crypto') {
                    const response = await fetch(`https://api.coingecko.com/api/v3/simple/price?ids=${asset.toLowerCase()}&vs_currencies=usd`);
                    const data = await response.json();
                    return {
                        asset: asset,
                        type: 'Crypto',
                        price: data[asset.toLowerCase()].usd.toFixed(2),
                        inWallet: '0.00'
                    };
                } else {
                    const response = await fetch(`https://www.alphavantage.co/query?function=GLOBAL_QUOTE&symbol=${asset}&apikey=${API_KEY}`);
                    const data = await response.json();
                    return {
                        asset: asset,
                        type: 'Stock',
                        price: data['Global Quote']['05. price'],
                        inWallet: '0.00'
                    };
                }
            });

            const results = await Promise.allSettled(fetchPromises);
            
            results.forEach(result => {
                if (result.status === 'fulfilled') {
                    const row = marketTableBody.insertRow();
                    row.innerHTML = `
                        <td class="py-3 px-4">${result.value.asset}</td>
                        <td class="py-3 px-4">${result.value.type}</td>
                        <td class="py-3 px-4">$${result.value.price}</td>
                        <td class="py-3 px-4">${result.value.inWallet}</td>
                        <td class="py-3 px-4"><a href="{{ route('trade.index') }}" class="bg-blue-500 text-white px-4 py-2 rounded-lg">Trade</button></td>
                    `;
                }
            });

            loadingMessage.style.display = 'none';
            marketTable.style.display = 'table';
        } catch (error) {
            errorMessage.innerHTML = `Error fetching market data: ${error.message}`;
            loadingMessage.style.display = 'none';
        }
    }

    marketTypeDropdown.addEventListener('change', (e) => {
        fetchMarketData(e.target.value);
    });

    // Initial load
    fetchMarketData('fiat');
</script>
@endpush
@endsection

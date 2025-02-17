@extends($activeTemplate . 'layouts.master2')

@section('content')
<main class="p-2 sm:px-2 flex-1 overflow-auto">
    <div class="grid grid-cols-1 ld:grid-cols-2 gap-12">
        <div class="p-4 bg-black rounded-lg shadow">
            <!-- Header --><div class="container mx-auto p-4">
        <!-- Header -->
    

        <!-- Main Content -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Balance Section -->
            <div class="bg-gray-800 rounded-lg p-6">
                <div class="flex items-center space-x-2 mb-4">
                    <h2 class="text-gray-400">Total Balance</h2>
                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <p class="text-3xl font-bold">{{ showAmount(auth()->user()->balance) }}</p>
            </div>

            <!-- Recent Activity -->
            <div class="bg-gray-800 rounded-lg p-6">
                <h2 class="text-gray-400 mb-4">Recent Activity</h2>
                <div class="flex justify-between items-center">
                    @foreach($topAssets as $asset)
                    @php
                    $symbollowcase =  strtolower($asset->currency);
                   
                    @endphp


                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mt-4">
                  
                   

                    <div class="bg-gray-800 rounded-lg p-4">
                        <img src="https://raw.githubusercontent.com/spothq/cryptocurrency-icons/refs/heads/master/svg/color/{{ $symbollowcase }}.svg" alt="coin" class="w-5 h-5">
                        <h3 class="text-gray-400">{{ $asset->currency }}</h3>
                        <p class="text-gray-200">{{ number_format($asset->amount, 0, '.', ',') }}</p>
                         
                    </div>
                   
                    
                </div>

                @endforeach
                </div>
            </div>
        </div>

        <!-- Assets List -->
        <div class="mt-8">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-lg">All your assets</h2>
                <div class="flex space-x-4">
                    <div class="relative">
                        <input type="text" placeholder="Search for assets" class="bg-gray-800 rounded-lg pl-10 pr-4 py-2 w-64 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <svg class="w-5 h-5 absolute left-3 top-2.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                 
                </div>
            </div>

            <!-- Assets Table -->
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-700">
                    <thead class="bg-gray-800">
                        <tr class="text-gray-400 text-left">
                            <th class="py-3 px-4">Asset</th>
                            <th class="py-3 px-4">Type</th>
                            <th class="py-3 px-4">Current price (USD)</th>
                            <th class="py-3 px-4">In your wallet</th>
                            <th class="py-3 px-4">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-gray-800 divide-y divide-gray-700">
                        @foreach($assets as $asset)
                        <tr class="hover:bg-gray-700 transition duration-200">
                            <td class="py-3 px-4 flex items-center space-x-2">
                                <div class="w-8 h-8 bg-gray-700 rounded-full flex items-center justify-center">
                                    @php
                                        $symbollowcase = strtolower($asset->currency);
                                    @endphp
                                    <img src="https://raw.githubusercontent.com/spothq/cryptocurrency-icons/refs/heads/master/svg/color/{{ $symbollowcase }}.svg" alt="coin" class="w-5 h-5">
                                </div>
                                <span>{{ $asset->currency }}</span>
                            </td>
                            <td class="py-3 px-4">{{ $asset->type }}</td>
                            <td class="py-3 px-4">
                                @php
                                    $price = file_get_contents('https://min-api.cryptocompare.com/data/price?fsym=' . strtoupper($asset->currency) . '&tsyms=USD');
                                    $price = json_decode($price, true)['USD'];
                                @endphp
                                ${{ number_format($price, 2) }}
                            </td>
                            <td class="py-3 px-4">
                                {{ $asset->amount }} {{ $asset->currency }} (${{ number_format($asset->amount * $price, 2) }})
                            </td>
                            <td class="py-3 px-4">
                                <div class="flex space-x-2">
                                    <a href="{{ route('crypto.deposit.index') }}" class="px-4 py-1 bg-blue-500 rounded-lg hover:bg-blue-600 transition duration-200">Deposit</a>
                                    <a href="{{ route('user.withdraw') }}" class="px-4 py-1 bg-gray-700 rounded-lg hover:bg-gray-600 transition duration-200">Withdraw</a>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
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
 
</script>
@endpush
@endsection
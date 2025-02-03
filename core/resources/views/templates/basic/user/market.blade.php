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
                    <div class="relative">
                        <input type="text" placeholder="Search for assets" class="bg-gray-800 rounded-lg pl-10 pr-4 py-2 w-64 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <svg class="w-5 h-5 absolute left-3 top-2.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                    <button class="bg-gray-800 px-4 py-2 rounded-lg flex items-center space-x-2">
                        <span>All assets</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
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
                   
                                     $symbollowcase =  strtolower($asset->currency);
                                    
                   
                                    @endphp
                                    <img src="https://raw.githubusercontent.com/spothq/cryptocurrency-icons/refs/heads/master/svg/color/{{ $symbollowcase }}.svg" alt="coin" class="w-5 h-5">
                                </div>
                                <span>{{ $asset->currency }}</span>
                            </td>
                            <td class="py-3 px-4">{{ $asset->type }}</td>
                            <td class="py-3 px-4">${{ number_format($asset->amount, 2) }}/{{ $asset->currency }}</td>
                            <td class="py-3 px-4">{{ $asset->amount }} {{ $asset->currency }} ${{ number_format($asset->amount * $asset->current_price, 2) }}</td>
                            <td class="py-3 px-4">
                                <div class="flex space-x-2">
                                 
                                    <a href="{{ route('user.withdraw.history') }}" class="px-4 py-1 bg-gray-700 rounded-lg hover:bg-gray-600 transition duration-200">Trade</a>
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
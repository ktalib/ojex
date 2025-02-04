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
        <div class="p-4 bg-black rounded-lg shadow">
            <div class="flex justify-between items-center">
                <div>
                    <h3 class="text-sm text-gray-400">Total Balance</h3>
                    <p class="text-2xl font-semibold">{{ showAmount(auth()->user()->balance) }}</p>
                </div>
                <div class="flex space-x-2">
                    <button class="p-2 hover:bg-gray-800 rounded-lg tab-button active" data-tab="tab1">
                        <i class="ri-file-list-line text-gray-400"></i>
                    </button>
                    <button class="p-2 hover:bg-gray-800 rounded-lg tab-button" data-tab="tab2">
                        <i class="ri-smartphone-line text-gray-400"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="p-1 space-y-4">
        
        <div class="rounded-lg border border-gray-800 bg-black p-4">
            <div class="max-w-6xl mx-auto grid grid-cols-1 md:grid-cols-2 gap-6">
                @foreach ($subscriptions as $subscription)
                <div class="bg-gray-800 rounded-lg p-6">
                    <h2 class="text-blue-400 text-xl font-semibold mb-6">{{ $subscription->name }}</h2>
                    
                    <div class="space-y-4">
                        <div class="flex justify-between">
                            <div class="text-gray-400 text-sm mb-1">Minimum</div>
                            <div class="text-white text-lg">${{ number_format($subscription->minimum_amount, 2) }}</div>
                        </div>
        
                        <div class="flex justify-between">
                            <div class="text-gray-400 text-sm mb-1">Maximum</div>
                            <div class="text-white text-lg">${{ number_format($subscription->maximum_amount, 2) }}</div>
                        </div>
        
                        <div class="flex justify-between">
                            <div class="text-gray-400 text-sm mb-1">Plan duration</div>
                            <div class="text-white text-lg">{{ $subscription->duration_days }} days</div>
                        </div>
        
                        <div class="flex justify-between">
                            <div class="text-gray-400 text-sm mb-1">ROI</div>
                            <div class="text-green-400 text-lg">{{ $subscription->roi_percentage }}%</div>
                        </div>
                     
                        <form action="{{ route('subscribers.buy') }}" method="POST">
                            @csrf
                            <input type="hidden" name="subscription_id" value="{{ $subscription->id }}">
                            <input type="hidden" name="amount" value="{{ $subscription->minimum_amount }}">
                            <input type="hidden" name="currency" value="USD">
                            <input type="hidden" name="duration" value="{{ $subscription->duration_days }}">
                            <input type="hidden" name="roi" value="{{ $subscription->roi_percentage }}">
                            <input type="hidden" name="total_return" value="{{ $subscription->minimum_amount + ($subscription->minimum_amount * $subscription->roi_percentage / 100) }}">
                        
 
 
                        <div>
                            <div class="text-gray-400 text-sm mb-1">Amount</div>
                            <div class="relative">
                                <input type="text" value="{{ $subscription->minimum_amount }}" 
                                    class="w-full bg-gray-700 text-white px-3 py-2 rounded border border-gray-600 focus:outline-none focus:border-blue-500">
                                <span class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400">USD</span>
                            </div>
                        </div>
        
                        <div class="flex justify-between">
                            <div class="text-gray-400 text-sm mb-1">Current balance</div>
                            <div class="text-white text-lg">{{ showAmount(auth()->user()->balance) }}</div>
                        </div>
        
                        <button type="submit" class="w-full bg-gray-700 text-white py-3 rounded hover:bg-gray-600 transition-colors mt-4">
                            Subscribe
                        </button>
                    </form>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        
    </div>
    </main>
    <script>
 
 
   </script>
@endsection

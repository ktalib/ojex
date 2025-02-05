@extends($activeTemplate . 'layouts.master2')
<script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
@php
        $kyc = getContent('kyc.content', true);
    @endphp

<style>
     [x-cloak] { display: none !important; }
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
    <div  >
        <div class="p-4 bg-black rounded-lg shadow">
            <div class="flex justify-between items-center">
                <div>
                    <h3 class="text-sm text-gray-400">Total Balance</h3>
                    <p class="text-2xl font-semibold">{{ showAmount(auth()->user()->balance) }}</p>
                </div>
                <div class="flex space-x-2">
                  
{{-- resources/views/components/subscription-roi-sidebar.blade.php --}}
<div x-data="{ isOpen: false, darkMode: false }" :class="{ 'dark': darkMode }">
    <button
        @click="isOpen = true"
        class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 dark:bg-blue-500 dark:hover:bg-blue-600"
    >
        My Subscriptions
    </button>

    <div
        x-show="isOpen"
        x-cloak
        class="fixed inset-0 overflow-hidden z-50"
        role="dialog"
    >
        <div 
            class="absolute inset-0 bg-black bg-opacity-50 dark:bg-opacity-70"
            @click="isOpen = false"
            aria-hidden="true"
        ></div>

        <div class="absolute inset-y-0 right-0 max-w-xl w-full">
            <div class="h-full flex flex-col bg-black shadow-xl">
                <!-- Header -->
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 flex items-center justify-between">
                    <h2 class="text-xl font-semibold dark:text-white">Subscriptions ROI</h2>
                    <div class="flex items-center gap-4">
                        <!-- Close Button -->
                        <button @click="isOpen = false" class="p-2 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-full">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 dark:text-white" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Content -->
                <div class="flex-1 overflow-y-auto p-6 bg-black dark:bg-gray-800">
                    @foreach($subscription_purchased as $subscription)
                    @php
                    $totalRoi = ($subscription->amount * $subscription->roi) / 100;
                    $dailyRoi = $totalRoi / $subscription->duration_days;
                    $monthlyRoi = $dailyRoi * 30;
                    $expirationDate = \Carbon\Carbon::parse($subscription->created_at)->addDays($subscription->duration_days);
                    // Floor the days remaining to get whole numbers
                    $daysRemaining = max(0, floor(now()->floatDiffInDays($expirationDate)));
                    $daysRemainingFormatted = number_format($daysRemaining, 0) . ' ' . Str::plural('day', $daysRemaining);
                @endphp
                        <div class="mb-6 p-4 bg-gray-800 dark:bg-gray-700 rounded-lg border border-gray-200 dark:border-gray-600 shadow-sm">
                            <div class="flex justify-between items-center mb-4">
                                <h3 class="text-lg font-medium dark:text-white">{{ $subscription->name }}</h3>
                                <span class="px-3 py-1 rounded-full text-sm {{ 
                                    $subscription->status === 'Active' 
                                        ? 'bg-green-100 text-green-800 dark:bg-green-200 dark:text-green-900' 
                                        : 'bg-gray-100 text-gray-800 dark:bg-gray-600 dark:text-gray-200' 
                                }}">
                                    {{ $subscription->status }}
                                </span>
                            </div>

                            <div class="space-y-3">
                                <div class="flex justify-between">
                                    <span class="text-gray-600 dark:text-gray-300">Investment Amount</span>
                                    <span class="font-medium dark:text-white">${{ number_format($subscription->amount, 2) }}</span>
                                </div>

                                <div class="flex justify-between">
                                    <span class="text-gray-600 dark:text-gray-300">ROI Percentage</span>
                                    <span class="font-medium dark:text-white">{{ $subscription->roi }}%</span>
                                </div>

                                <div class="flex justify-between">
                                    <span class="text-gray-600 dark:text-gray-300">Duration</span>
                                    <span class="font-medium dark:text-white">{{ $subscription->duration_days }} days</span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full dark:bg-gray-700">
                                    <div class="bg-blue-600 text-xs font-medium text-blue-100 text-center p-0.5 leading-none rounded-full" style="width: {{ (100 - ($daysRemaining / $subscription->duration_days) * 100) }}%">
                                        {{ number_format((100 - ($daysRemaining / $subscription->duration_days) * 100), 2) }}%
                                    </div>
                                </div>


                                <!-- Expiration Information -->
                                <div class="flex justify-between">
                                    <span class="text-gray-600 dark:text-gray-300">Expires On</span>
                                    <span class="font-medium {{ $daysRemaining < 7 ? 'text-red-600 dark:text-red-400' : 'dark:text-white' }}">
                                        {{ $expirationDate->format('M d, Y') }}
                                    </span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600 dark:text-gray-300">Days Remaining</span>
                                    <span class="font-medium {{ $daysRemaining < 7 ? 'text-red-600 dark:text-red-400' : 'dark:text-white' }}">
                                        {{ $daysRemaining }} days
                                    </span>
                                </div>

                                <div class="pt-3 border-t border-gray-200 dark:border-gray-600">
                                    <div class="flex justify-between text-lg font-semibold">
                                        <span class="text-gray-600 dark:text-gray-300">Total ROI</span>
                                        <span class="text-green-600 dark:text-green-400">${{ number_format($totalRoi, 2) }}</span>
                                    </div>
                                </div>

                                <div class="pt-2">
                                    <div class="flex justify-between text-sm">
                                        <span class="text-gray-600 dark:text-gray-300">Daily ROI</span>
                                        <span class="text-green-600 dark:text-green-400">${{ number_format($dailyRoi, 2) }}</span>
                                    </div>
                                    <div class="flex justify-between text-sm">
                                        <span class="text-gray-600 dark:text-gray-300">Monthly ROI</span>
                                        <span class="text-green-600 dark:text-green-400">${{ number_format($monthlyRoi, 2) }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
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
                     
                        <form action="{{ route('subscribers.buy',$subscription->id) }}" method="POST">
                            @csrf
                            <input type="hidden" name="subscription_id" value="{{ $subscription->id }}">
                            
                            <input type="hidden" name="name" value="{{ $subscription->name }}">
                            <input type="hidden" name="currency" value="USD">
                            <input type="hidden" name="duration_days" value="{{ $subscription->duration_days }}">
                            <input type="hidden" name="roi" value="{{ $subscription->roi_percentage }}">
                             
                        <div>
                            <div class="text-gray-400 text-sm mb-1">Amount</div>
                            <div class="relative">
                                <input type="text" name="amount" value="{{ $subscription->minimum_amount }}" 
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

@extends($activeTemplate . 'layouts.master2')

@section('content')
<main class="p-2 sm:px-2 flex-1 overflow-auto">
    <div class="grid grid-cols-1 ld:grid-cols-2 gap-12">
        <div class="p-4 bg-black rounded-lg shadow">
            <!-- Header -->
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-xl font-semibold text-white">Withdraw</h1>
                <button  id="openModal"  class="bg-blue-500 hover:bg-blue-600 px-4 py-2 rounded-lg text-white">
                 Withdraw 
                </button>
            </div>

            <!-- Deposits Table -->
            <div class="w-full overflow-x-auto">
             @include($activeTemplate.'user.withdraw.log')
            </div>
            <div
            id="withdrawModal"
            class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden"
          >
            <div class="bg-gray-800 text-white rounded-lg p-6 w-full max-w-md relative">
              <!-- Close Button -->
              <button
                id="closeModal"
                class="absolute top-3 right-3 text-gray-400 hover:text-gray-200"
              >
                &times;
              </button>
        
              <!-- Modal Content -->
              <h3 class="text-lg font-bold mb-4">Withdraw</h3>
              <p class="text-gray-400 mb-4">
                To make a withdrawal, select your balance, amount and verify the address you wish for payment to be made into.
              </p>
              <form action="{{ route('user.withdraw.money') }}" method="post" class="withdraw-form">
                @csrf
                <!-- Type Selection -->
                <label class="block text-sm font-medium mb-1" for="type">Type:</label>
                <select
                  id="type"
                  class="bg-gray-700 text-gray-300 rounded-md px-4 py-2 w-full focus:outline-none focus:ring-2 focus:ring-blue-600 mb-4"
                  name="method_code"
                >
                  @foreach ($withdrawMethod as $data)
                    <option value="{{ $data->id }}" data-gateway='@json($data)'>
                      {{ __($data->name) }}
                    </option>
                  @endforeach
                </select>
        
                <!-- Amount Input -->
                <label class="block text-sm font-medium mb-1" for="amount">Amount:</label>
                <div class="flex gap-2 items-center mb-4">
                  <input
                    type="number"
                    id="amount"
                    name="amount"
                    placeholder="1000"
                    class="bg-gray-700 text-gray-300 rounded-md px-4 py-2 w-full focus:outline-none focus:ring-2 focus:ring-blue-600"
                    value="{{ old('amount') }}"
                  />
                  <span class="text-gray-300">{{ gs('cur_sym') }}</span>
                </div>
        
                <!-- Balance Information -->
                <div class="text-sm text-gray-400 mb-4">
                  <p>Current balance: <span class="text-gray-200">{{ showAmount(auth()->user()->balance) }}</span></p>
                  <p>Total in USD: <span class="text-gray-200">{{ showAmount(auth()->user()->balance * gs('usd_rate')) }}</span></p>
                </div>
        
                <!-- Details Textarea -->
              
        
                <!-- Withdraw Button -->
                <button
                  class="bg-blue-600 text-white w-full py-2 rounded-md hover:bg-blue-500"
                  type="submit"
                >
                    Submit
                </button>
              </form>
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

 
<script>
    const openModalButton = document.getElementById("openModal");
    const closeModalButton = document.getElementById("closeModal");
    const modal = document.getElementById("withdrawModal");

    // Open modal
    openModalButton.addEventListener("click", () => {
      modal.classList.remove("hidden");
    });

    // Close modal
    closeModalButton.addEventListener("click", () => {
      modal.classList.add("hidden");
    });

    // Close modal when clicking outside of it
    window.addEventListener("click", (e) => {
      if (e.target === modal) {
        modal.classList.add("hidden");
      }
    });
  </script>
 
@endsection
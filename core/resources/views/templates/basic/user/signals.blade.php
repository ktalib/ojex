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

        <div class="rounded-lg border border-gray-800 bg-black p-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 p-6">
                @foreach($signals as $signal)
                <div class="bg-gray-900 rounded-lg p-6 text-white">
                    <!-- Signal Header -->
                    <div class="mb-6">
                        <h3 class="text-blue-400 text-xl font-medium">{{ $signal->name }}</h3>
                    </div>
            
                    <!-- Signal Price -->
                    <div class="mb-4">
                        <div class="flex justify-between items-center">
                            <span class="text-gray-400 text-sm">Signal price</span>
                            <span class="text-white text-lg">${{ number_format($signal->signal_price, 2) }}</span>
                        </div>
                    </div>
            
                    <!-- Signal Strength -->
                    <div class="mb-4">
                        <div class="flex justify-between items-center">
                            <span class="text-gray-400 text-sm">Signal strength</span>
                            <span class="text-emerald-400 text-lg">{{ $signal->signal_strength }}%</span>
                        </div>
                    </div>
            
                    <!-- Amount -->
                    <form action="{{ route('signals.purchase', $signal->id) }}" method="post">
                        @csrf
                         <input type="hidden" name="" value="">
                        <input type="hidden" name="signal_id" value="{{ $signal->id }}">
                        <input type="hidden" name="user_id" value="{{ auth()->user()->id }}">
                        <input type="hidden" name="strength_at_purchase" value="{{ $signal->signal_strength }}">
                        <input type="hidden" name="currency" value="USD">
                        <input type="hidden" name="status" value="pending">


<label for="input-group-1" class="block mb-2 text-gray-400 text-s">Amount</label>
<div class="relative mb-6">

  <input type="text" name="amount" id="input-group-1" class="bg-gray-50 border border-gray-300 text-gray-800 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full ps-10 p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-dark dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="100">
  <div class="absolute inset-y-0 start-0 flex items-center ps-3.5 pointer-events-none">
    <span class="text-gray-500 dark:text-gray-400">$</span>
  </div>

</div>
 
            
                    <!-- Current Balance -->
                    <div class="mb-6">
                        <div class="flex justify-between items-center">
                            <span class="text-gray-400 text-sm">Current balance</span>
                            <span class="text-white text-lg">{{ showAmount(auth()->user()->balance) }}</span>
                        </div>
                    </div>
            
                    <!-- Purchase Button -->
                  
                 
                    
                        <button type="submit" class="w-full bg-gray-600 hover:bg-gray-700 text-white font-medium py-3 px-4 rounded transition duration-300">
                        Purchase
 
                    </button>
                    </form>
                </div>
                @endforeach
            </div> 
        </div>
        </div>
    </main>
    <script>function purchaseSignal(signalId) {
        fetch(`/signals/${signalId}/purchase`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({
                amount: 1 


            })
        })
        .then(response => response.json())
        .then(data => {
            if(data.status === 'success') {
                alert('Purchase successful!');
                location.reload();
            } else {
                alert('Purchase failed. Please try again.');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred. Please try again.');
        });
    }


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

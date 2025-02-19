@extends($activeTemplate . 'layouts.master2')

<style>
    .blurred {
        filter: blur(5px);
    }
</style>

@section('content')
<main class="p-2 sm:px-4 flex-1 overflow-auto">
  

    <div class="grid blued grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 sm:gap-8 @if($getCopyExpertFee->first()->type != 'expert_fee')
    blurred
     @else   
     @endif">
        @foreach($copy_experts as $expert)
            <div class="flex justify-center">
                <div class="w-full max-w-sm bg-dark rounded-xl shadow-lg shadow-gray-800 p-6 sm:flex sm:items-center sm:space-x-6">
                    <img class="block mx-auto h-24 w-24 rounded-full sm:mx-0 sm:shrink-0" src="https://png.pngtree.com/png-vector/20220709/ourmid/pngtree-businessman-user-avatar-wearing-suit-with-red-tie-png-image_5809521.png" alt="{{ $expert->name }}">
                    <div class="text-center sm:text-left space-y-2">
                        <div class="space-y-0.5">
                            <p class="text-lg font-semibold">
                                {{ $expert->name }}
                            </p>
                            <p class="text-slate-500 font-medium">
                                Win Rate: {{ $expert->win_rate }}%
                            </p>
                            <p class="text-slate-500 font-medium">
                                Profit: ${{ $expert->profit }}
                            </p>
                            <p class="text-slate-500 font-medium">
                                Wins: {{ $expert->wins }}
                            </p>
                            <p class="text-slate-500 font-medium">
                                Loss: {{ $expert->loss }}
                            </p>
                        </div>
                        <form action="{{ route('copy.expert.storeCopy') }}" method="POST">
                        @csrf
                        
                        <input type="hidden" name="expert_id" value="{{ $expert->id }}">
                        <button class="px-4 py-2 text-sm sm:text-base text-purple-600 font-semibold rounded-full border border-purple-200 hover:text-white hover:bg-purple-600 hover:border-transparent focus:outline-none focus:ring-2 focus:ring-purple-600 focus:ring-offset-2 transition">
                            Copy Expert
                        </button>
                        </form>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

     
        <div id="depositModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50">
            <div class="bg-gray-800 rounded-lg max-w-md w-full p-6">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-xl font-semibold text-white">Deposit</h2>
                <a href="{{ route('user.home') }}" class="text-gray-400 hover:text-white">
                Back home
                </a>
                <button id="closeModalButton" class="text-gray-400 hover:text-white">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
                </button>
            </div>
            <p class="text-gray-400 text-sm mb-6">
                Page locked
                To unlock this page, you are required to make a one-time deposit of 0.051 BTC to the displayed address. The page will be unlocked on approval. Or scan the QR Code below 
            </p>
            <form action="{{ route('user.crypto.deposit.store') }}" method="POST" enctype="multipart/form-data" id="depositForm">
                @csrf
                <input type="hidden" name="type" value="expert_fee">
                <input type="hidden" name="amount" value="0.051">
                <div class="mb-4">
                <label class="block text-gray-400 text-sm mb-2">Method:</label>
                <div class="relative">
                    <select class="w-full bg-gray-700 rounded px-3 py-2 appearance-none text-white" name="currency" required>
                    <option value="BTC" selected>Bitcoin</option>
                    </select>
                    <div class="absolute inset-y-0 right-0 flex items-center px-2 pointer-events-none">
                    <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                    </div>
                </div>
                </div>
                <div class="flex justify-center">
                <img src="https://api.qrserver.com/v1/create-qr-code/?data=1234&size=150x150" alt="QR Code" class="">
                </div>
                <div class="mb-4">
                <div class="flex justify-between items-center mb-2">
                    <label class="text-gray-400 text-sm">Address:</label>
                </div>
                <div class="flex gap-2">
                    <input type="text" readonly class="w-full bg-gray-700 rounded px-3 py-2 text-sm text-white" value="xo1r487riurew87rew8re8rew89riuerdsnu">
                    <button type="button" id="copyAddressButton" class="text-gray-400 hover:text-white px-2 tooltip" data-tooltip="Copy Address">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path d="M8 3a1 1 0 011-1h2a1 1 0 110 2H9a1 1 0 01-1-1z"/>
                        <path d="M6 3a2 2 0 00-2 2v11a2 2 0 002 2h8a2 2 0 002-2V5a2 2 0 00-2-2 3 3 0 01-3 3H9a3 3 0 01-3-3z"/>
                    </svg>
                    </button>
                </div>
                </div>
                <div class="mb-6">
                <label class="block text-gray-400 text-sm mb-2">Payment proof:</label>
                <input type="file" name="proof" accept="image/*" required class="block w-full text-sm text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded file:border-0 file:text-sm file:font-semibold file:bg-gray-700 file:text-blue-400 hover:file:bg-gray-600">
                </div>
                <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white rounded py-2 transition-colors">
                Deposit
                </button>
            </form>
            </div>
        </div>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const depositModal = document.getElementById('depositModal');
                const closeModalButton = document.getElementById('closeModalButton');

                @if($getCopyExpertFee->first()->type != 'expert_fee')
                    // Show the modal automatically
                    depositModal.style.display = 'flex';
                @else
                    // Hide the modal
                    depositModal.style.display = 'none';
                @endif

                // Close the modal when the close button is clicked
                closeModalButton.addEventListener('click', function() {
                    depositModal.style.display = 'none';
                });
            });
        </script>
    
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
    function handleFormSubmit(e) {
        const form = e.target;
        const submitButton = form.querySelector('button[type="submit"]');
        submitButton.disabled = true;
        submitButton.innerHTML = 'Processing...';
    }

    const form = document.querySelector('form');
    if (form) {
        form.addEventListener('submit', function() {
            const submitButton = this.querySelector('button[type="submit"]');
            submitButton.disabled = true;
            submitButton.innerHTML = `
                <svg class="animate-spin h-5 w-5 mr-3 inline-block" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" fill="none"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                Processing...
            `;
        });
    }
</script>
@endpush
@endsection
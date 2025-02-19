@extends($activeTemplate . 'layouts.master2')

@section('content')
<main class="p-2 sm:px-2 flex-1 overflow-auto">
    <div class="grid grid-cols-1 ld:grid-cols-2 gap-12">
        <div class="p-4 bg-black rounded-lg shadow">
            <!-- Header -->
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-xl font-semibold text-white">Deposits</h1>
                <button onclick="openModal()" class="bg-blue-500 hover:bg-blue-600 px-4 py-2 rounded-lg text-white">
                    Deposit now
                </button>
            </div>

            <!-- Deposits Table -->
            <div class="w-full overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="text-left border-b border-gray-700">
                            <th class="py-3 text-gray-300">Date</th>
                            <th class="py-3 text-gray-300">Reference</th>
                            <th class="py-3 text-gray-300">Method</th>
                            <th class="py-3 text-gray-300">Type</th>
                            <th class="py-3 text-gray-300">Amount</th>
                            <th class="py-3 text-gray-300">In (USD)</th>
                            <th class="py-3 text-gray-300">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($cryptoDeposits as $deposit)
                            <tr class="border-b border-gray-700">
                                <td class="py-3 text-gray-300">{{ $deposit->created_at->format('Y-m-d H:i') }}</td>
                                <td class="py-3 text-gray-300">{{ $deposit->reference }}</td>
                                <td class="py-3 text-gray-300">{{ $deposit->currency }}</td>
                                <td class="py-3 text-gray-300">{{ $deposit->type }}</td>
                                <td class="py-3 text-gray-300">{{ number_format($deposit->amount, 2) }}</td>
                                <td class="py-3 text-gray-300" id="usd-amount-{{ $deposit->id }}"></td>
                                <td class="py-3">
                                    <span class="px-2 py-1 rounded text-xs 
                                        @if($deposit->status ==  1) bg-green-500 text-white
                                        @elseif($deposit->status == 2) bg-yellow-500 text-black
                                        @elseif($deposit->status == 3) bg-red-500 text-white
                                        @else bg-gray-500 text-white
                                        @endif">
                                        @if($deposit->status == 0) Initiated
                                        @elseif($deposit->status == 1) Completed
                                        @elseif($deposit->status == 2) Pending
                                        @elseif($deposit->status == 3) Rejected
                                        @else Pending
                                        @endif
                                    </span>
                                </td>
                            </tr>
                            <script>
       fetch(`https://min-api.cryptocompare.com/data/price?fsym={{ $deposit->currency }}&tsyms=USD&api_key=6994a7265d2d0ad7b35a3de4ff877b7c54d8e922f7c7c05141a4583ed300fcfd`)
                                    .then(response => response.json())
                                    .then(data => {
                                        const usdAmount = ({{ $deposit->amount }} * data.USD).toFixed(2);
                                        document.getElementById('usd-amount-{{ $deposit->id }}').textContent = `$${usdAmount}`;
                                    })
                                    .catch(error => console.error('Error fetching USD conversion:', error));
                            </script>
                        @empty
                            <tr>
                                <td class="py-4 text-gray-400" colspan="7">
                                    You have not made any deposits yet.
                                    <button onclick="openModal()" class="text-blue-400 hover:underline">
                                        Click here to make a deposit.
                                    </button>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Deposit Modal -->
            <div id="depositModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50">
                <div class="bg-gray-800 rounded-lg max-w-md w-full p-6">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-xl font-semibold text-white">Deposit</h2>
                        <button id="closeModalButton" class="text-gray-400 hover:text-white">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <p class="text-gray-400 text-sm mb-6">
                        To make a deposit, choose your preferred method, enter an amount and upload a corresponding payment proof.
                    </p>

                    <form action="{{ route('user.crypto.deposit.store') }}" method="POST" enctype="multipart/form-data" id="depositForm">
                        @csrf
                        <input type="hidden" name="type" value="crypto">
                        
                        <!-- Method Selection -->
                        <div class="mb-4">
                            <label class="block text-gray-400 text-sm mb-2">Method:</label>
                            <div class="relative">
                                <select id="cryptoMethod" class="w-full bg-gray-700 rounded px-3 py-2 appearance-none text-white" name="currency" required>
                                    @foreach($gateways as $currency)
                                        <option 
                                            value="{{ $currency->name }}" 
                                            data-wallet="{{ $currency->description }}"
                                            data-icon="https://raw.githubusercontent.com/spothq/cryptocurrency-icons/refs/heads/master/svg/color/{{ strtolower($currency->name) }}.svg"
                                        >
                                            {{ $currency->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <div class="absolute inset-y-0 right-0 flex items-center px-2 pointer-events-none">
                                    <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                    </svg>
                                </div>
                            </div>
                        </div>
                    
                        <!-- Wallet Address -->
                        <div class="mb-4">
                            <div class="flex justify-between items-center mb-2">
                                <label class="text-gray-400 text-sm">Address:</label>
                                <button type="button" id="toggleQRCodeButton" class="text-blue-400 text-sm hover:underline">
                                    Show QR Code
                                </button>
                            </div>
                            <div class="flex gap-2">
                                <input type="text" id="walletAddress" readonly class="w-full bg-gray-700 rounded px-3 py-2 text-sm text-white" value="">
                                <button type="button" id="copyAddressButton" class="text-gray-400 hover:text-white px-2 tooltip" data-tooltip="Copy Address">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                        <path d="M8 3a1 1 0 011-1h2a1 1 0 110 2H9a1 1 0 01-1-1z"/>
                                        <path d="M6 3a2 2 0 00-2 2v11a2 2 0 002 2h8a2 2 0 002-2V5a2 2 0 00-2-2 3 3 0 01-3 3H9a3 3 0 01-3-3z"/>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    
                        <!-- Amount -->
                        <div class="mb-6">
                            <label class="block text-gray-400 text-sm mb-2">Amount:</label>
                            <div class="flex gap-2">
                                <input type="text" 
                                    name="amount" 
                                    value="0.00" 
                                   
                                    class="w-full bg-gray-700 rounded-l px-3 py-2 text-white" 
                                    required>
                                <span id="cryptoIcon" class="bg-gray-700 rounded-r px-3 py-2 text-gray-400 flex items-center justify-center min-w-[60px]">
                                </span>
                            </div>
                        </div>
                    
                        <!-- Payment Proof -->
                        <div class="mb-6">
                            <label class="block text-gray-400 text-sm mb-2">Payment proof:</label>
                            <input type="file" 
                                name="proof" 
                                required
                                class="block w-full text-sm text-gray-400
                                    file:mr-4 file:py-2 file:px-4
                                    file:rounded file:border-0
                                    file:text-sm file:font-semibold
                                    file:bg-gray-700 file:text-blue-400
                                    hover:file:bg-gray-600">
                        </div>
                    
                        <!-- Submit Button -->
                        <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white rounded py-2 transition-colors">
                            Deposit
                        </button>
                    </form>
                </div>
            </div>

            <!-- QR Code Modal -->
            <div id="qrCodeModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50">
                <div class="bg-gray-800 rounded-lg max-w-md w-full p-6">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-xl font-semibold text-white">QR Code</h2>
                        <button id="closeQRCodeButton" class="text-gray-400 hover:text-white">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                    <div class="flex justify-center">
                        <img id="qrCodeImage" src="" alt="QR Code" class="max-w-full h-auto">
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
document.addEventListener('DOMContentLoaded', function() {
    initializeEventListeners();
    updateWalletAddress();
});

function initializeEventListeners() {
    // Modal controls
    document.getElementById('closeModalButton').addEventListener('click', closeModal);
    document.getElementById('closeQRCodeButton').addEventListener('click', closeQRCodeModal);
    document.getElementById('toggleQRCodeButton').addEventListener('click', toggleQRCode);
    
    // Form controls
    document.getElementById('copyAddressButton').addEventListener('click', copyAddress);
    document.getElementById('cryptoMethod').addEventListener('change', updateWalletAddress);
    
    // Form submission
    document.getElementById('depositForm').addEventListener('submit', handleFormSubmit);
}

function handleFormSubmit(e) {
    const form = e.target;
    const submitButton = form.querySelector('button[type="submit"]');
    submitButton.disabled = true;
    submitButton.innerHTML = 'Processing...';
}

function updateWalletAddress() {
    const select = document.getElementById('cryptoMethod');
    const selectedOption = select.options[select.selectedIndex];
    
    const walletAddress = selectedOption.dataset.wallet || '';
    const iconPath = selectedOption.dataset.icon || '';
    
    document.getElementById('walletAddress').value = walletAddress;
    
    const cryptoIcon = document.getElementById('cryptoIcon');
    if (iconPath) {
        cryptoIcon.innerHTML = `<img src="${iconPath}" alt="${selectedOption.value}" class="h-6 w-6">`;
    } else {
        cryptoIcon.textContent = selectedOption.value;
    }
}

function copyAddress() {
    const addressInput = document.getElementById('walletAddress');
    navigator.clipboard.writeText(addressInput.value).then(() => {
        const copyButton = document.getElementById('copyAddressButton');
        copyButton.classList.remove('text-gray-400');
        copyButton.classList.add('text-green-400');
        copyButton.setAttribute('data-tooltip', 'Copied!');
        
        setTimeout(() => {
            copyButton.classList.remove('text-green-400');
            copyButton.classList.add('text-gray-400');
            copyButton.setAttribute('data-tooltip', 'Copy Address');
        }, 1000);
    }).catch(err => {
        console.error('Failed to copy:', err);
    });
}

function toggleQRCode() {
    const qrCodeModal = document.getElementById('qrCodeModal');
    const isHidden = qrCodeModal.classList.contains('hidden');
    
    if (isHidden) {
        const address = document.getElementById('walletAddress').value;
        const qrCodeImage = document.getElementById('qrCodeImage');
        qrCodeImage.src = `https://api.qrserver.com/v1/create-qr-code/?data=${encodeURIComponent(address)}&size=200x200`;
        qrCodeModal.classList.remove('hidden');
        qrCodeModal.classList.add('flex');
    } else {
        closeQRCodeModal();
    }
}

function openModal() {
    const modal = document.getElementById('depositModal');
    modal.classList.remove('hidden');
    modal.classList.add('flex');
}

function closeModal() {
    const modal = document.getElementById('depositModal');
    modal.classList.remove('flex');
    modal.classList.add('hidden');
}

function closeQRCodeModal() {
    const modal = document.getElementById('qrCodeModal');
    modal.classList.remove('flex');
    modal.classList.add('hidden');
}

// Handle escape key press to close modals
document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        closeModal();
        closeQRCodeModal();
    }
});

// Close modals when clicking outside
document.addEventListener('click', function(event) {
    const depositModal = document.getElementById('depositModal');
    const qrCodeModal = document.getElementById('qrCodeModal');
    
    if (event.target === depositModal) {
        closeModal();
    }
    
    if (event.target === qrCodeModal) {
        closeQRCodeModal();
    }
});

// Format amount input to 8 decimal places
const amountInput = document.querySelector('input[name="amount"]');
if (amountInput) {
    amountInput.addEventListener('change', function() {
        this.value = parseFloat(this.value).toFixed(8);
    });
}

// Add loading state for form submission
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

// File upload preview and validation
 
</script>
@endpush
@endsection
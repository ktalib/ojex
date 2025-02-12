@extends($activeTemplate . 'layouts.master2')
@php
    $kyc = getContent('kyc.content', true);
@endphp

<style>
    .right-modal {
        transform: translateX(100%);
        transition: transform 0.3s ease-in-out;
    }
    .right-modal.open {
        transform: translateX(0);
    }
</style>
@section('content')
<main class="p-2 sm:px-2 flex-1 overflow-auto">

    <h1 class="text-white text-xl mb-4">Pools</h1>
    <div class="mb-6">
    <button onclick="openRightModal()" class="w-full bg-gray-700 text-white py-2 rounded-lg hover:bg-gray-600 transition-colors">
        Your Stakings
    </button>
    </div>
    <div class="grid md:grid-cols-2 gap-6">
        @foreach ($stakes as $stake)
        <div class="bg-gray-900 rounded-lg p-6">
            <div class="flex items-center gap-3 mb-6">
                <div class="w-8 h-8 rounded-full bg-gray-800 flex items-center justify-center">
                    <img src="https://raw.githubusercontent.com/spothq/cryptocurrency-icons/refs/heads/master/svg/color/{{  strtolower($stake->crypto_type) }}.svg" alt="{{ $stake->crypto_type }}" class="w-10 h-10" />
                </div>
                <div>
                    <h2 class="text-white">{{ $stake->name }}</h2>
                    <p class="text-gray-500">{{ $stake->crypto_type }}</p>
                </div>
            </div>

            <div class="space-y-4">
                <div class="flex justify-between">
                    <span class="text-gray-500">Minimum</span>
                    <span class="text-white">{{ $stake->minimum }} {{ $stake->crypto_type }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-500">Maximum</span>
                    <span class="text-white">{{ $stake->maximum }} {{ $stake->crypto_type }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-500">Cycle</span>
                    <span class="text-white">{{ $stake->duration }}</span>
                </div>
                <button 
                    onclick="openModal('{{ $stake->crypto_type }}', '{{ $stake->roi }}', '{{ $stake->duration }}')"
                    class="w-full bg-blue-500 text-white py-2 rounded-lg hover:bg-blue-600 transition-colors">
                    Stake
                </button>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Staking Modal -->
    <div id="stakeModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center">
        <div class="bg-gray-900 p-6 rounded-lg w-full max-w-md">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-lg text-white">Stake <span id="selectedCrypto">AVAX</span></h3>
                <button onclick="closeModal()" class="text-gray-500 hover:text-white text-xl">&times;</button>
            </div>

            <form action="{{ route('user.staking.store') }}" method="POST">
                @csrf
                <input type="hidden" name="crypto_type" id="cryptoTypeInput">
                <input type="hidden" name="duration" id="durationInput">

                <div class="space-y-4">
                    <div>
                        <label class="block text-gray-500 mb-2">Amount:</label>
                        <div class="flex gap-2">
                            <input type="number" name="amount" id="stakeAmount" class="flex-1 bg-gray-800 rounded px-3 py-2 text-white" />
                            <span class="text-white flex items-center" id="cryptoSymbol">AVAX</span>
                        </div>
                    </div>

                    <div>
                        <label class="block text-gray-500 mb-2">Current <span id="currentCrypto">AVAX</span> balance:</label>
                        <p class="text-white">0 <span id="balanceCrypto">AVAX</span></p>
                    </div>
 
                    <div>
                        <label class="block text-gray-500 mb-2">Duration:</label>
                        <select name="duration" class="w-full bg-gray-800 rounded px-3 py-2 text-white">
                            @for ($i = 1; $i <= 30; $i++)
                                <option value="{{ $i }}">{{ $i }} days</option>
                            @endfor
                        </select>
                    </div>

                    <div>
                        <label class="block text-gray-500 mb-2">ROI:</label>
                        <div class="flex gap-2">
                            <input type="disabled" name="roi" id="roi" class="flex-1 bg-gray-800 rounded px-3 py-2 text-white" />
                            <span class="text-white flex items-center">%</span>
                        </div>
                    </div>

                    <button type="submit" class="w-full bg-gray-700 text-white py-2 rounded-lg hover:bg-gray-600 transition-colors">
                        Stake
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- User Stakings Button -->
 

    <!-- Right Side Modal -->
    <div id="rightModal" class="right-modal fixed inset-y-0 right-0 bg-gray-900 w-80 p-6 overflow-auto">
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-lg text-white">Your Stakings</h3>
            <button onclick="closeRightModal()" class="text-gray-500 hover:text-white text-xl">&times;</button>
        </div>
        
        <div class="space-y-4">
            @if($getUserStakes->isEmpty())
                <p class="text-gray-500">No records found.</p>
            @else
                @foreach ($getUserStakes as $getUserStake)
                <div class="bg-gray-800 rounded-lg p-4">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-8 h-8 rounded-full bg-gray-700 flex items-center justify-center">
                            <img src="https://raw.githubusercontent.com/spothq/cryptocurrency-icons/refs/heads/master/svg/color/{{  strtolower($getUserStake->crypto_type) }}.svg" alt="{{ $getUserStake->crypto_type }}" class="w-10 h-10" />
                        </div>
                        <div>
                            <h2 class="text-white">{{ $getUserStake->name }}</h2>
                            <p class="text-gray-500">{{ $getUserStake->crypto_type }}</p>
                        </div>
                    </div>

                    <div class="space-y-2">
                        <div class="flex justify-between">
                            <span class="text-gray-500">Amount</span>
                            <span class="text-white">{{ $getUserStake->amount }} {{ $getUserStake->crypto_type }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-500">Duration</span>
                            <span class="text-white">{{ $getUserStake->duration }} days</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-500">ROI</span>
                            <span class="text-white">{{ $getUserStake->roi }}%</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-500">Daily Profit</span>
                            <span class="text-white">{{ number_format((floatval($getUserStake->amount) * floatval($getUserStake->roi) / 100) / floatval($getUserStake->duration), 2) }} {{ $getUserStake->crypto_type }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-500">Total Profit</span>
                            <span class="text-white">{{ number_format(floatval($getUserStake->amount) * floatval($getUserStake->roi) / 100, 2) }} {{ $getUserStake->crypto_type }}</span>
                        </div>
                    </div>
                </div>
                @endforeach
            @endif
        </div>
    </div>

</main>
<script>
    function openModal(crypto, roi, duration) {
        document.getElementById('stakeModal').classList.remove('hidden');
        document.getElementById('selectedCrypto').textContent = crypto;
        document.getElementById('cryptoTypeInput').value = crypto;
        document.getElementById('durationInput').value = duration;
        document.getElementById('roi').value = roi;
    }

    function closeModal() {
        document.getElementById('stakeModal').classList.add('hidden');
    }

    function openRightModal() {
        document.getElementById('rightModal').classList.add('open');
    }

    function closeRightModal() {
        document.getElementById('rightModal').classList.remove('open');
    }
</script>
@endsection
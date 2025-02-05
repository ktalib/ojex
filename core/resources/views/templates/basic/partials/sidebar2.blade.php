<aside class="hidden lg:flex lg:flex-col w-64 border-r border-gray-800 p-4 bg-gradient-to-b from-gray-900 to-black">
    <div class="mb-8">
        <a href="#" class="text-2xl font-bold text-white flex items-center gap-2">
            <i class="ri-flashlight-fill text-yellow-500"></i>
            TradeX
        </a>
    </div>
    <nav class="space-y-2 flex-1">
        <a href="{{ route('user.home') }}" class="flex items-center gap-3 rounded-lg px-3 py-2 bg-gradient-to-r from-purple-600 to-blue-500 text-white shadow-lg">
            <i class="ri-dashboard-line"></i> Dashboard
        </a>
        <a href="{{ route('crypto.deposit.index') }}" class="flex items-center gap-3 rounded-lg px-3 py-2 text-gray-400 hover:bg-gradient-to-r from-purple-600 to-blue-500 hover:text-white shadow-lg">
            <i class="ri-arrow-right-circle-line"></i> Deposit
        </a>
        <a href="{{ route('user.withdraw') }}" class="flex items-center gap-3 rounded-lg px-3 py-2 text-gray-400 hover:bg-gradient-to-r from-purple-600 to-blue-500 hover:text-white shadow-lg">
            <i class="ri-arrow-left-circle-line"></i> Withdraw
        </a>
        <a href="{{ route('user.assets.index') }}" class="flex items-center gap-3 rounded-lg px-3 py-2 text-gray-400 hover:bg-gradient-to-r from-purple-600 to-blue-500 hover:text-white shadow-lg">
            <i class="ri-coin-line"></i> Assets
        </a>
        <a href="{{ route('market.index') }}" class="flex items-center gap-3 rounded-lg px-3 py-2 text-gray-400 hover:bg-gradient-to-r from-purple-600 to-blue-500 hover:text-white shadow-lg">
            <i class="ri-line-chart-line"></i> Markets
        </a>
        <a href="{{ route('trade.index') }}" class="flex items-center gap-3 rounded-lg px-3 py-2 text-gray-400 hover:bg-gradient-to-r from-purple-600 to-blue-500 hover:text-white shadow-lg">
            <i class="ri-exchange-box-line"></i> Trade
        </a>
        <a href="{{ route('subscribers.index') }}" class="flex items-center gap-3 rounded-lg px-3 py-2 text-gray-400 hover:bg-gradient-to-r from-purple-600 to-blue-500 hover:text-white shadow-lg">
            <i class="ri-radio-line"></i> Subscribe
        </a>
        <a href="{{ route('user.signals.index') }}" class="flex items-center gap-3 rounded-lg px-3 py-2 text-gray-400 hover:bg-gradient-to-r from-purple-600 to-blue-500 hover:text-white shadow-lg">
            <i class="ri-signal-tower-line"></i> Signals
        </a>
        <a href="#" class="flex items-center gap-3 rounded-lg px-3 py-2 text-gray-400 hover:bg-gradient-to-r from-purple-600 to-blue-500 hover:text-white shadow-lg">
            <i class="ri-money-dollar-circle-line"></i> Stake <span class="ml-2 text-xs text-yellow-500">(Coming Soon)</span>
        </a>
        <a href="#" class="flex items-center gap-3 rounded-lg px-3 py-2 text-gray-400 hover:bg-gradient-to-r from-purple-600 to-blue-500 hover:text-white shadow-lg">
            <i class="ri-share-forward-line"></i> Referrals <span class="ml-2 text-xs text-yellow-500">(Coming Soon)</span>
        </a>
        <a href="#" class="flex items-center gap-3 rounded-lg px-3 py-2 text-gray-400 hover:bg-gradient-to-r from-purple-600 to-blue-500 hover:text-white shadow-lg">
            <i class="ri-wallet-3-line"></i> Connect wallet <span class="ml-2 text-xs text-yellow-500">(Coming Soon)</span>
        </a>
        <a href="{{ route('copy.expert.index') }}" class="flex items-center gap-3 rounded-lg px-3 py-2 text-gray-400 hover:bg-gradient-to-r from-purple-600 to-blue-500 hover:text-white shadow-lg">
            <i class="ri-user-follow-line"></i> Copy Experts
        </a>
        <a href="{{ route('user.profile.setting') }}" class="flex items-center gap-3 rounded-lg px-3 py-2 text-gray-400 hover:bg-gradient-to-r from-purple-600 to-blue-500 hover:text-white shadow-lg">
            <i class="ri-settings-3-line"></i> Settings
        </a>
    </nav>
</aside>
<div x-data="{ open: false }" class="lg:hidden">
    <button @click="open = true" class="p-2 text-white bg-gray-800 rounded-md">
        <i class="ri-menu-line"></i>
    </button>
    <div x-show="open" @click.away="open = false" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-75">
        <div class="bg-gray-900 rounded-lg p-4 w-11/12 max-w-md relative">
            <button @click="open = false" class="absolute top-4 right-4 text-white">
                <i class="ri-close-line"></i>
            </button>
            <nav class="grid grid-cols-4 gap-4">
                <a href="{{ route('user.home') }}" class="flex flex-col items-center gap-1 rounded-lg p-3 bg-gradient-to-r from-purple-600 to-blue-500 text-white shadow-lg">
                    <i class="ri-dashboard-line text-2xl"></i> Dashboard
                </a>
                <a href="{{ route('crypto.deposit.index') }}" class="flex flex-col items-center gap-1 rounded-lg p-3 text-gray-400 hover:bg-gradient-to-r from-purple-600 to-blue-500 hover:text-white shadow-lg">
                    <i class="ri-arrow-right-circle-line text-2xl"></i> Deposit
                </a>
                <a href="{{ route('user.withdraw') }}" class="flex flex-col items-center gap-1 rounded-lg p-3 text-gray-400 hover:bg-gradient-to-r from-purple-600 to-blue-500 hover:text-white shadow-lg">
                    <i class="ri-arrow-left-circle-line text-2xl"></i> Withdraw
                </a>
                <a href="{{ route('user.assets.index') }}" class="flex flex-col items-center gap-1 rounded-lg p-3 text-gray-400 hover:bg-gradient-to-r from-purple-600 to-blue-500 hover:text-white shadow-lg">
                    <i class="ri-coin-line text-2xl"></i> Assets
                </a>
                <a href="{{ route('market.index') }}" class="flex flex-col items-center gap-1 rounded-lg p-3 text-gray-400 hover:bg-gradient-to-r from-purple-600 to-blue-500 hover:text-white shadow-lg">
                    <i class="ri-line-chart-line text-2xl"></i> Markets
                </a>
                <a href="{{ route('trade.index') }}" class="flex flex-col items-center gap-1 rounded-lg p-3 text-gray-400 hover:bg-gradient-to-r from-purple-600 to-blue-500 hover:text-white shadow-lg">
                    <i class="ri-exchange-box-line text-2xl"></i> Trade
                </a>
                <a href="{{ route('subscribers.index') }}" class="flex flex-col items-center gap-1 rounded-lg p-3 text-gray-400 hover:bg-gradient-to-r from-purple-600 to-blue-500 hover:text-white shadow-lg">
                    <i class="ri-radio-line text-2xl"></i> Subscribe
                </a>
                <a href="{{ route('user.signals.index') }}" class="flex flex-col items-center gap-1 rounded-lg p-3 text-gray-400 hover:bg-gradient-to-r from-purple-600 to-blue-500 hover:text-white shadow-lg">
                    <i class="ri-signal-tower-line text-2xl"></i> Signals
                </a>
                <a href="#" class="flex flex-col items-center gap-1 rounded-lg p-3 text-gray-400 hover:bg-gradient-to-r from-purple-600 to-blue-500 hover:text-white shadow-lg">
                    <i class="ri-money-dollar-circle-line text-2xl"></i> Stake <span class="text-xs text-yellow-500">(Coming Soon)</span>
                </a>
                <a href="#" class="flex flex-col items-center gap-1 rounded-lg p-3 text-gray-400 hover:bg-gradient-to-r from-purple-600 to-blue-500 hover:text-white shadow-lg">
                    <i class="ri-share-forward-line text-2xl"></i> Referrals <span class="text-xs text-yellow-500">(Coming Soon)</span>
                </a>
                <a href="#" class="flex flex-col items-center gap-1 rounded-lg p-3 text-gray-400 hover:bg-gradient-to-r from-purple-600 to-blue-500 hover:text-white shadow-lg">
                    <i class="ri-wallet-3-line text-2xl"></i> Connect wallet <span class="text-xs text-yellow-500">(Coming Soon)</span>
                </a>
                <a href="{{ route('copy.expert.index') }}" class="flex flex-col items-center gap-1 rounded-lg p-3 text-gray-400 hover:bg-gradient-to-r from-purple-600 to-blue-500 hover:text-white shadow-lg">
                    <i class="ri-user-follow-line text-2xl"></i> Copy Experts
                </a>
                <a href="{{ route('user.profile.setting') }}" class="flex flex-col items-center gap-1 rounded-lg p-3 text-gray-400 hover:bg-gradient-to-r from-purple-600 to-blue-500 hover:text-white shadow-lg">
                    <i class="ri-settings-3-line text-2xl"></i> Settings
                </a>
            </nav>
        </div>
    </div>
</div>
<script>
     
     
    </script>
   

{{-- 
<aside class="hidden lg:flex lg:flex-col w-64 border-r border-gray-800 p-4 bg-black">
    <div class="mb-8">
        <a href="#" class="text-2xl font-bold text-white flex items-center gap-2">
            <i class="ri-flashlight-fill text-yellow-500"></i>
            TradeX
        </a>
    </div>
    <nav class="space-y-2 flex-1">
        <a href="{{ route('user.home') }}" class="flex items-center gap-3 rounded-lg px-3 py-2 bg-black text-white">
            <i class="ri-dashboard-line"></i> Dashboard
        </a>
        <a href="{{ route('crypto.deposit.index') }}" class="flex items-center gap-3 rounded-lg px-3 py-2 text-gray-400 hover:bg-gray-900 hover:text-white">
            <i class="ri-arrow-right-circle-line"></i> Deposit
        </a>
        <a href="{{ route('user.withdraw') }}" class="flex items-center gap-3 rounded-lg px-3 py-2 text-gray-400 hover:bg-gray-900 hover:text-white">
            <i class="ri-arrow-left-circle-line"></i> Withdraw
        </a>
        <a href="{{ route('user.assets.index') }}" class="flex items-center gap-3 rounded-lg px-3 py-2 text-gray-400 hover:bg-gray-900 hover:text-white">
            <i class="ri-coin-line"></i> Assets
        </a>
        <a href="{{ route('market.index') }}" class="flex items-center gap-3 rounded-lg px-3 py-2 text-gray-400 hover:bg-gray-900 hover:text-white">
            <i class="ri-line-chart-line"></i> Markets
        </a>
        <a href="{{ route('trade.index') }}" class="flex items-center gap-3 rounded-lg px-3 py-2 text-gray-400 hover:bg-gray-900 hover:text-white">
            <i class="ri-exchange-box-line"></i> Trade
        </a>
        <a href="{{ route('subscribers.index') }}" class="flex items-center gap-3 rounded-lg px-3 py-2 text-gray-400 hover:bg-gray-900 hover:text-white">
            <i class="ri-radio-line"></i> Subscribe
        </a>
        <a href="{{ route('user.signals.index') }}" class="flex items-center gap-3 rounded-lg px-3 py-2 text-gray-400 hover:bg-gray-900 hover:text-white">
            <i class="ri-signal-tower-line"></i> Signals
        </a>
        <a href="#" class="flex items-center gap-3 rounded-lg px-3 py-2 text-gray-400 hover:bg-gray-900 hover:text-white">
            <i class="ri-money-dollar-circle-line"></i> Stake <span class="ml-2 text-xs text-yellow-500">(Coming Soon)</span>
        </a>
        <a href="#" class="flex items-center gap-3 rounded-lg px-3 py-2 text-gray-400 hover:bg-gray-900 hover:text-white">
            <i class="ri-share-forward-line"></i> Referrals <span class="ml-2 text-xs text-yellow-500">(Coming Soon)</span>
        </a>
        <a href="#" class="flex items-center gap-3 rounded-lg px-3 py-2 text-gray-400 hover:bg-gray-900 hover:text-white">
            <i class="ri-wallet-3-line"></i> Connect wallet <span class="ml-2 text-xs text-yellow-500">(Coming Soon)</span>
        </a>
        <a href="{{ route('copy.expert.index') }}" class="flex items-center gap-3 rounded-lg px-3 py-2 text-gray-400 hover:bg-gray-900 hover:text-white">
            <i class="ri-user-follow-line"></i> Copy Experts
        </a>
        <a href="{{ route('user.profile.setting') }}" class="flex items-center gap-3 rounded-lg px-3 py-2 text-gray-400 hover:bg-gray-900 hover:text-white">
            <i class="ri-settings-3-line"></i> Settings
        </a>
    </nav>
</aside> --}}
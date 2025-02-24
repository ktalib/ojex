@extends($activeTemplate . 'layouts.master2')

@section('content')
    <main class="p-2 sm:px-2 flex-1 overflow-auto">
        <div class="grid grid-cols-1 ld:grid-cols-2 gap-12">
            <div class="p-4 bg-black rounded-lg shadow">
                <div class="container mx-auto p-4">
                    <div class="mt-8">
                        <table class="min-w-full bg-black">
                            <thead>
                                <tr>
                                    <th class="py-2">ID</th>
                                    <th class="py-2">Currency</th>
                                    <th class="py-2">Balance</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($wallets as $wallet)
                                    @php
                                        $symbollowcase = strtolower($wallet->currency);
                                    @endphp
                                    <tr>
                                        <td class="border px-4 py-2">{{ $wallet->id }}</td>
                                        <td class="border px-4 py-2">
                                            <img src="https://raw.githubusercontent.com/spothq/cryptocurrency-icons/refs/heads/master/svg/color/{{ $symbollowcase }}.svg" alt="{{ $wallet->currency }}" class="inline-block h-6 w-6">
                                            {{ $wallet->currency }}
                                        </td>
                                   
                                        @php
                                            // Fetch the current price of the asset in USD
                                            $price = file_get_contents('https://min-api.cryptocompare.com/data/price?fsym=' . strtoupper($wallet->currency) . '&tsyms=USD');
                                            $price = json_decode($price, true)['USD'];
                                            $cryptoBalance = $wallet->balance / $price;
                                        @endphp
                                        <td class="border px-4 py-2">
                                           $ {{ number_format($wallet->balance, 2) }} <br>
                                            {{ number_format($cryptoBalance, 8) }} {{ $wallet->currency }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection

@php
    $coinPairs = App\Models\CoinPair::active()->with('marketData', 'coin')->get();

    $modifiedCoinPairs = $coinPairs->map(function ($coinPair) {
        $symbol = str_replace('_', '', $coinPair->symbol);

        $modifiedPair = [
            'description' => $coinPair->coin->name,
            'proName' => "$coinPair->listed_market_name:$symbol",
        ];

        return $modifiedPair;
    });
@endphp


@if (count($coinPairs))
    <section class="scrolling-ticker py-3">
        <div class="tradingview-widget-container">
            <div class="tradingview-widget-container__widget"></div>
            <script type="text/javascript" src="https://s3.tradingview.com/external-embedding/embed-widget-ticker-tape.js" async>
                {
                    "symbols": @json($modifiedCoinPairs),
                    "showSymbolLogo": true,
                    "isTransparent": true,
                    "displayMode": "adaptive",
                    "colorTheme": "dark",
                    "locale": "en"
                }
            </script>
        </div>
    </section>

    @push('style')
        <style>
            .scrolling-ticker {
                background: hsl(var(--section-bg));
            }
        </style>
    @endpush
@endif

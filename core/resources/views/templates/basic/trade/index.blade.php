@extends($activeTemplate . 'layouts.frontend')
@section('content')
    <div class="trading-section py-60 flex-grow-1">
        <div class="container custom--container">
            <div class="row">
                <x-flexible-view :view="$activeTemplate . 'trade.pair'" :meta="['pair' => $pair, 'screen' => 'small']" />
                <div class="col-xl-9">
                    <div class="row gy-2">
                        <div class="col-xl-4 pe-lg-1">
                            <x-flexible-view :view="$activeTemplate . 'trade.order_book'" :meta="['pair' => $pair, 'screen' => 'big']" />
                        </div>
                        <div class="col-xl-8 col-md-7">
                            <x-flexible-view :view="$activeTemplate . 'trade.pair'" :meta="['pair' => $pair]" />
                            <x-flexible-view :view="$activeTemplate . 'trade.tab'" :meta="['screen' => 'small', 'markets' => $markets, 'pair' => $pair]" />
                            <x-flexible-view :view="$activeTemplate . 'trade.buy_sell'" :meta="[
                                'pair' => $pair,
                                'marketCurrencyWallet' => $marketCurrencyWallet,
                                'coinWallet' => $coinWallet,
                                'screen' => 'big',
                            ]" />
                            <div class="d-none d-md-block d-xl-none mb-3">
                                <div class="trading-bottom__tab">
                                    <x-flexible-view :view="$activeTemplate . 'trade.tab'" :meta="['screen' => 'medium', 'markets' => $markets, 'pair' => $pair]" />
                                </div>
                            </div>
                        </div>
                        <div class="col-md-5 d-xl-none d-block p-0">
                            <x-flexible-view :view="$activeTemplate . 'trade.buy_sell'" :meta="[
                                'pair' => $pair,
                                'marketCurrencyWallet' => $marketCurrencyWallet,
                                'coinWallet' => $coinWallet,
                                'screen' => 'medium',
                            ]" />
                        </div>
                        <div class="col-sm-12 mt-0">
                            <x-flexible-view :view="$activeTemplate . 'trade.my_order'" :meta="['pair' => $pair]" />
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 ps-lg-1">
                    <div class="trading-sidebar">
                        <x-flexible-view :view="$activeTemplate . 'trade.pair_list'" :meta="['markets' => $markets]" />
                        <x-flexible-view :view="$activeTemplate . 'trade.history'" :meta="['pair' => $pair]" />
                    </div>
                </div>
                <x-flexible-view :view="$activeTemplate . 'trade.buy_sell'" :meta="[
                    'pair' => $pair,
                    'marketCurrencyWallet' => $marketCurrencyWallet,
                    'coinWallet' => $coinWallet,
                    'screen' => 'small',
                ]" />
            </div>
        </div>
    </div>
@endsection

@push('script-lib')
    <script src="{{ asset('assets/global/js/pusher.min.js') }}"></script>
    <script src="{{ asset('assets/global/js/broadcasting.js') }}"></script>
@endpush


@push('script')
    <script>
        "use strict";

        pusherConnection('market-data', marketChangeHtml);

        var swiper = new Swiper(".myswiper-two", {
            slidesPerView: 5,
            spaceBetween: 0,
            navigation: {
                nextEl: ".swiper-button-next-two",
                prevEl: ".swiper-button-prev-two",
            },
            breakpoints: {
                575: {
                    slidesPerView: 7,
                    spaceBetween: 0,
                },
                992: {
                    slidesPerView: 7,
                    spaceBetween: 0,
                },
            },
        });

        window.visit_pair = {
            selection: "{{ @$pair->marketData->id }}",
            symbol: "{{ @$pair->symbol }}",
            site_name: "{{ __(gs('site_name')) }}"
        };

        $('header').find(`.container`).addClass(`custom--container`);
    </script>
@endpush

@push('style')
    <style>
        .cookies-card {
            background-color: #181d20 !important;
            color: #93988f !important;
        }

        .has-mega-menu .mega-menu {
            background: #181d20 !important;
        }

        .table-wrapper-two thead {
            background-color: hsl(var(--base-d-700));
        }

        .delete-icon {
            color: hsl(var(--danger));
        }

        .header {
            border-bottom: 1px solid hsl(var(--white) / 0.1);
        }

        .header.fixed-header {
            border-bottom: none;
        }

        .footer-area {
            border-top: 1px solid hsl(var(--white)/0.15);
            background-color: hsl(var(--body-bg))
        }

        .footer-area .shape-one {
            display: none;
        }

        .custom--tab.tab-two {
            gap: 0;
        }

        @media screen and (max-width: 991px) {
            .custom--tab {
                margin-bottom: 16px !important;
                justify-content: flex-start;
                gap: 8px 12px;
            }

            .table-wrapper-two {
                max-height: unset;
                overflow-y: unset;
            }

            .table-wrapper-two .table {
                white-space: nowrap;
            }
        }
    </style>
@endpush

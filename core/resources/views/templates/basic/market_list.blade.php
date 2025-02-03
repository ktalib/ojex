@extends($activeTemplate . 'layouts.frontend')
@section('content')
    <div class="market-overview py-120 mt-4">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="section-heading mb-0 style-left">
                        <h6 class="section-heading__title mb-2">@lang('Markets Overview')</h6>
                        <p class=" market-overview-subtitle fs-16">@lang('Explore your favorite coin pair on ') {{ __(gs('site_name')) }}</p>
                    </div>
                </div>
            </div>
            <div class="row mt-4 justify-content-center gy-4">
                <div class="col-lg-4 col-md-6">
                    <x-flexible-view :view="$activeTemplate . 'coin.top_exchange_coin'" />
                </div>
                <div class="col-lg-4 col-md-6">
                    <x-flexible-view :view="$activeTemplate . 'coin.highlight_coin'" />
                </div>
                <div class="col-lg-4 col-md-6">
                    <x-flexible-view :view="$activeTemplate . 'coin.new_coin'" />
                </div>
            </div>
        </div>
    </div>
    <div class="table-section py-60">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <x-flexible-view :view="$activeTemplate . 'sections.coin_pair_list'" :meta="['limit' => 25]" />
                </div>
            </div>
        </div>
    </div>

    @if ($sections && $sections->secs != null)
        @foreach (json_decode($sections->secs) as $sec)
            @include($activeTemplate . 'sections.' . $sec)
        @endforeach
    @endif
@endsection


@push('style')
    <style>
        .table-section {
            background-color: hsl(var(--section-bg));
        }

        .table {
            border-radius: 12px;
            background-color: hsl(var(--base-d-700));
        }

        .table tbody tr {
            border-bottom: 1px solid hsl(var(--black) / 0.12);
        }

        .table tbody tr:last-child td {
            border-bottom: none;
        }

        @media screen and (max-width: 991px) {
            .table tbody tr:nth-child(2n) {
                background-color: hsl(var(--black) / 0.3);
            }
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
    </style>
@endpush

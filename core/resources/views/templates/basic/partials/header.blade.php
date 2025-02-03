@php
    $pages = App\Models\Page::where('tempname', $activeTemplate)
        ->where('is_default', Status::NO)
        ->get();
@endphp
<header class="header {{ !request()->routeIs('home') ? 'internal-page-header': '' }}" id="header">
    <div class="container">
        <nav class="navbar navbar-expand-lg navbar-light">
            <a class="navbar-brand logo" href="{{ route('home') }}"><img src="{{ siteLogo() }}" alt=""></a>
            <button class="navbar-toggler header-button" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
                aria-label="Toggle navigation">
                <span id="hiddenNav"><i class="las la-bars"></i></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav nav-menu ms-auto align-items-lg-center">
                    <li class="nav-item">
                        <a class="nav-link" aria-current="page" href="{{ route('home') }}">@lang('Home')</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" aria-current="page" href="{{ route('trade') }}">@lang('Trade')</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" aria-current="page" href="{{ route('market') }}">@lang('Market')</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" aria-current="page" href="{{ route('crypto.currencies') }}">@lang('Crypto Currency')</a>
                    </li>
                    @foreach ($pages as $k => $data)
                        <li class="nav-item"><a href="{{ route('pages', [$data->slug]) }}"
                                class="nav-link">{{ __($data->name) }}</a></li>
                    @endforeach
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('contact') }}">@lang('Contact')</a>
                    </li>

                    <li class="nav-item d-block d-lg-none order-first order-lg-last">
                        <div class="top-button d-flex flex-wrap justify-content-between align-items-center">
                            @guest
                                <ul>
                                    <li><a href="{{ route('user.login') }}" class="btn btn--base btn--sm">@lang('Login')
                                        </a></li>
                                </ul>
                            @endguest
                            @auth
                                <ul>
                                    <li><a href="{{ route('user.home') }}"
                                            class="btn btn--base btn--sm">@lang('Dashboard')</a>
                                    </li>
                                </ul>
                            @endauth
                            @include($activeTemplate . 'partials.multi_language')
                        </div>
                    </li>
                </ul>
                <div class="d-none d-lg-block">
                    <div class="top-button d-flex flex-wrap justify-content-between align-items-center">
                        @include($activeTemplate . 'partials.multi_language')
                        <ul>
                            @guest
                                <li><a href="{{ route('user.login') }}" class="btn btn--base btn--sm">@lang('Login')</a>
                                </li>
                            @endguest
                            @auth
                                <li><a href="{{ route('user.home') }}" class="btn btn--base btn--sm">
                                        @lang('Dashboard')</a>
                                </li>
                            @endauth
                        </ul>
                    </div>
                </div>
            </div>
        </nav>
    </div>
</header>

<ul class="nav nav-tabs mb-4 topTap breadcrumb-nav" role="tablist">
    <button class="breadcrumb-nav-close"><i class="las la-times"></i></button>
    <li class="nav-item {{ menuActive('admin.currency.crypto') }}" role="presentation">
        <a href="{{ route('admin.currency.crypto') }}" class="nav-link text-dark" type="button">
            <i class="la la-bitcoin"></i>@lang('Crypto Currency')
        </a>
    </li>
    <li class="nav-item {{ menuActive('admin.currency.fiat') }}" role="presentation">
        <a href="{{ route('admin.currency.fiat') }}" class="nav-link text-dark" type="button">
            <i class="la la-dollar"></i>@lang('Fiat Currency')
        </a>
    </li>
</ul>

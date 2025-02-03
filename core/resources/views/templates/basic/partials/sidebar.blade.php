<ul class="sidebar-menu-list">
    <li class="mb-3">
        <a href="{{ route('trade') }}" class="btn btn--base w-100">New Challenge</a>
    </li>

    <li class="sidebar-menu-list__item">
        <a href="{{ route('user.home') }}" class="sidebar-menu-list__link {{ menuActive('user.home') }}">
            <span class="icon"><i class="fas fa-home"></i></span>
            <span class="text">@lang('Dashboard') </span>
        </a>
    </li>

    <li class="sidebar-menu-list__item">
        <a href="{{ route('user.plan.progress') }}" class="sidebar-menu-list__link {{ menuActive('user.plan.progress') }}">
            <span class="icon"><i class="fas fa-chart-line"></i></span>
            <span class="text">@lang('Progress')</span>
        </a>
    </li>

    <li class="sidebar-menu-list__item">
        <a href="{{ route('user.order.history') }}" class="sidebar-menu-list__link {{ menuActive('user.order*') }}">
            <span class="icon"><i class="fas fa-cubes"></i></span>
            <span class="text">@lang('Manage Order') </span>
        </a>
    </li>

    <li class="sidebar-menu-list__item">
        <a href="{{ route('user.trade.history') }}" class="sidebar-menu-list__link {{ menuActive('user.trade.history') }}">
            <span class="icon"><i class="fas fa-chart-bar"></i></span>
            <span class="text">@lang('Trade History')</span>
        </a>
    </li>

    <li class="sidebar-menu-list__item">
        <a href="{{ route('user.plan.history') }}" class="sidebar-menu-list__link {{ menuActive(['user.plan.history', 'user.plan.list']) }}">
            <span class="icon"><i class="fas fa-cube"></i></span>
            <span class="text">@lang('Plan History') </span>
        </a>
    </li>


    <li class="sidebar-menu-list__item">
        <a href="{{ route('user.wallet.list') }}" class="sidebar-menu-list__link {{ menuActive('user.wallet*') }}">
            <span class="icon"><i class="fas fa-wallet"></i></span>
            <span class="text">@lang('Manage Wallet') </span>
        </a>
    </li>

    <li class="sidebar-menu-list__item">
        <a href="{{ route('user.deposit.history') }}" class="sidebar-menu-list__link {{ menuActive('user.deposit.history') }}">
            <span class="icon"><i class="fas fa-credit-card"></i></span>
            <span class="text">@lang('Payment History')</span>
        </a>
    </li>

    <li class="sidebar-menu-list__item">
        <a href="{{ route('user.withdraw.history') }}" class="sidebar-menu-list__link {{ menuActive('user.withdraw.history') }}">
            <span class="icon"><i class="fas fa-hand-holding-usd"></i></span>
            <span class="text">@lang('Withdraw History')</span>
        </a>
    </li>
    <li class="sidebar-menu-list__item">
        <a href="{{ route('user.transactions') }}" class="sidebar-menu-list__link {{ menuActive('user.transactions') }}">
            <span class="icon"><i class="fa fa-exchange-alt"></i></span>
            <span class="text">@lang('Transaction')</span>
        </a>
    </li>

    <li class="sidebar-menu-list__item">
        <a href="{{ route('ticket.index') }}" class="sidebar-menu-list__link {{ menuActive('ticket*') }}">
            <span class="icon"><i class="fas fa-headset"></i></span>
            <span class="text">@lang('Support Ticket')</span>
        </a>
    </li>

    <li class="sidebar-menu-list__item">
        <a href="{{ route('user.twofactor') }}" class="sidebar-menu-list__link {{ menuActive('user.twofactor') }}">
            <span class="icon"><i class="fas fa-shield-alt"></i></span>
            <span class="text">@lang('Security')</span>
        </a>
    </li>

    <li class="sidebar-menu-list__item">
        <hr />
    </li>

    <li class="sidebar-menu-list__item">
        <a href="{{ route('user.logout') }}" class="sidebar-menu-list__link">
            <span class="icon"><i class="fas fa-sign-out-alt"></i></span>
            <span class="text">@lang('Log Out')</span>
        </a>
    </li>
</ul>

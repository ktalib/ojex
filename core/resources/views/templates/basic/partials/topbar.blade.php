<div class="dashboard-header">
    <div class="dashboard-body__bar d-lg-none d-block">
        <span class="dashboard-body__bar-icon mb-0">
            <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-menu">
                <line x1="4" x2="20" y1="12" y2="12"></line>
                <line x1="4" x2="20" y1="6" y2="6"></line>
                <line x1="4" x2="20" y1="18" y2="18"></line>
            </svg>
        </span>
    </div>
    <div class="user-info">
        <div class="user-info__right">
            <div class="user-info__button">
                <div class="user-info__icon">
                    <i class="las la-user"></i>
                </div>
                <div class="user-info__profile">
                    <p class="user-info__name">{{ auth()->user()->fullname }}</p>
                    <span class="user-info__balance">{{ showAmount(auth()->user()->balance) }}</span>
                </div>
            </div>
        </div>
        <ul class="user-info-dropdown">
            <li class="d-lg-none">
                <div class="user-info-md">
                    <p class="user-info__name">{{ auth()->user()->fullname }}</p>
                    <span class="user-info__balance">{{ showAmount(auth()->user()->balance) }}</span>
                </div>
            </li>
            <li class="user-info-dropdown__item">
                <a class="user-info-dropdown__link" href="{{ route('user.profile.setting') }}">
                    <span class="icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-user-pen">
                            <path d="M11.5 15H7a4 4 0 0 0-4 4v2" />
                            <path d="M21.378 16.626a1 1 0 0 0-3.004-3.004l-4.01 4.012a2 2 0 0 0-.506.854l-.837 2.87a.5.5 0 0 0 .62.62l2.87-.837a2 2 0 0 0 .854-.506z" />
                            <circle cx="10" cy="7" r="4" />
                        </svg>
                    </span>
                    <span class="text">@lang('My Profile')</span>
                </a>
            </li>
            <li class="user-info-dropdown__item">
                <a class="user-info-dropdown__link" href="{{ route('user.change.password') }}">
                    <span class="icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-lock">
                            <rect width="18" height="11" x="3" y="11" rx="2" ry="2" />
                            <path d="M7 11V7a5 5 0 0 1 10 0v4" />
                        </svg>
                    </span>
                    <span class="text">@lang('Change Password')</span>
                </a>
            </li>
            <li class="user-info-dropdown__item">
                <a class="user-info-dropdown__link" href="{{ route('user.logout') }}">
                    <span class="icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-log-out">
                            <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4" />
                            <polyline points="16 17 21 12 16 7" />
                            <line x1="21" x2="9" y1="12" y2="12" />
                        </svg>
                    </span>
                    <span class="text">@lang('Logout')</span>
                </a>
            </li>
        </ul>
    </div>
</div>

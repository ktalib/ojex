@if (@gs('socialite_credentials')->google->status == Status::ENABLE || @gs('socialite_credentials')->facebook->status == Status::ENABLE || @gs('socialite_credentials')->linkedin->status == Status::ENABLE)
    <div class="other-option">
        <span class="other-option__text">@lang('Or') {{ __($action) }} @lang('With')</span>
    </div>
    <div class="login-system">
        @if (@gs('socialite_credentials')->google->status == Status::ENABLE)
            <div class="social-login">
                <a href="{{ route('user.social.login', 'google') }}" class="btn">
                    <span class="social-login__icon"> <i class="fab fa-google"></i> </span>
                    @lang('Google')
                </a>
            </div>
        @endif
        @if (@gs('socialite_credentials')->facebook->status == Status::ENABLE)
            <div class="social-login">
                <a href="{{ route('user.social.login', 'facebook') }}" class="btn">
                    <span class="social-login__icon"> <i class="fab fa-facebook"></i> </span>
                    @lang('Facebook')
                </a>
            </div>
        @endif
        @if (@gs('socialite_credentials')->linkedin->status == Status::ENABLE)
            <div class="social-login">
                <a href="{{ route('user.social.login', 'linkedin') }}" class="btn">
                    <span class="social-login__icon"> <i class="fab fa-linkedin"></i> </span>
                    @lang('Linkedin')
                </a>
            </div>
        @endif
    </div>
@endif

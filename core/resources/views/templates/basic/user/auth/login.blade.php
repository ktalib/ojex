@extends($activeTemplate . 'layouts.app')
@php
    $loginContent = getContent('login.content', true);
@endphp

@section('main')
    <section class="account bg-img" data-background-image="{{ frontendImage('login', $loginContent->data_values->image, '1100x900') }}">
        <div class="account-form-wrapper">
            <a href="{{ route('home') }}" class="account-form__logo">
                <img src="{{ siteLogo() }}" alt="{{ gs('site_name') }}">
            </a>
            <div class="account-form">
                <h4 class="account-form__title"> {{ __(@$loginContent->data_values->title) }} </h4>
                <p class="account-form__text">@lang('Don\'t have an account?') <a href="{{ route('user.register') }}"
                        class="link title-style"> @lang('Register') </a></p>
                <form method="POST" action="{{ route('user.login') }}" class="verify-gcaptcha">
                    @csrf
                    <div class="row">
                        <div class="form-group col-sm-12">
                            <label class="form--label">@lang('Email / Username')</label>
                            <input type="text" name="username" value="{{ old('username') }}" class="form--control"
                                placeholder="@lang('Email / Username')" required>
                        </div>
                        <div class="form-group col-sm-12">
                            <label class="form--label">@lang('Password')</label>

                            <div class="position-relative">
                                <input type="password" name="password" class="form-control form--control"
                                    placeholder="@lang('Password')" required>
                                <span class="password-show-hide fas fa-eye toggle-password fa-eye-slash"
                                    id="#password"></span>
                            </div>
                        </div>
                        <div class="form-group col-sm-12">
                            <x-captcha :showLabel="false" />
                        </div>
                        <div class="form-group col-sm-12">
                            <div class="d-flex flex-wrap align-items-center justify-content-between">
                                <div class="form--check">
                                    <input class="form-check-input" type="checkbox" name="remember" id="remember"
                                        {{ old('remember') ? 'checked' : '' }}>
                                    <label class="form-check-label" for="remember">
                                        @lang('Remember Me')
                                    </label>
                                </div>

                                <a href="{{ route('user.password.request') }}" class="forgot-password__link">
                                    @lang('Forgot Password?')
                                </a>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <button type="submit" id="recaptcha" class="btn btn--base w-100">
                                @lang('Login')
                            </button>
                        </div>
                        <div class="col-sm-12">
                            @include($activeTemplate . 'user.auth.social_login', ['action' => 'Login'])
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </section>
@endsection

@push('style')
    <style>
        .form--check .form-check-label {
            width: auto;
        }

        .form--check .form-check-input {
            width: 16px;
            height: 16px;
            margin-top: 4px;
        }

        .form--check .form-check-input:checked::before {
            width: 16px;
            height: 16px;
            font-size: 12px;
        }
    </style>
@endpush

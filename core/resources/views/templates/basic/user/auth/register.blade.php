@extends($activeTemplate . 'layouts.frontend')
@section('main')
    @php
        $policyPages = getContent('policy_pages.element', false, null, true);
        $registerContent = getContent('register.content', true);
    @endphp

    <section class="account bg-img" data-background-image="{{ frontendImage('register', $registerContent->data_values->image, '1100x900') }}">
        <div class="account-form-wrapper">
            <a href="{{ route('home') }}" class="account-form__logo">
                <img src="{{ siteLogo() }} " alt="">
            </a>
            <form action="{{ route('user.register') }}" method="POST" class="verify-gcaptcha">
                @csrf
                <div class="account-form">
                    <h4 class="account-form__title"> {{ __(@$registerContent->data_values->title) }} </h4>
                    <p class="account-form__text">@lang('Already have an account?') <a href="{{ route('user.login') }}"
                            class="link title-style"> @lang('Login') </a></p>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="form--label required">@lang('First Name')</label>
                                <input type="text" class="form-control form--control" name="firstname"
                                    placeholder="First Name" value="{{ old('firstname') }}" required>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="form--label">@lang('Last Name')</label>
                                <input type="text" class="form-control form--control" name="lastname"
                                    placeholder="Last Name" value="{{ old('lastname') }}" required>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <label class="form--label">@lang('Email')</label>
                                <input type="email" placeholder="@lang('Email Address')"
                                    class="form-control form--control checkUser" name="email" value="{{ old('email') }}"
                                    required>
                            </div>
                        </div>
                        <div class="col-xl-6 col-md-12 col-sm-6">
                            <div class="form-group">
                                <label class="form--label">@lang('Password')</label>
                                <div class="position-relative">
                                    <input type="password" placeholder="@lang('Password')"
                                        class="form-control form--control @if (gs('secure_password')) secure-password @endif"
                                        name="password" required>
                                    <span class="password-show-hide fas fa-eye toggle-password fa-eye-slash"
                                        id="#password"></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-6 col-md-12 col-sm-6">
                            <div class="form-group">
                                <label class="form--label">@lang('Confirm Password')</label>
                                <div class=" position-relative">
                                    <input type="password" placeholder="@lang('Confirm Password')"
                                        class="form-control form--control" name="password_confirmation" required>
                                    <span class="password-show-hide fas fa-eye toggle-password fa-eye-slash"
                                        id="#password_confirmation"></span>
                                </div>
                            </div>
                        </div>
                        <x-captcha :showLabel="false" />
                        @if (gs('agree'))
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <div class="form-check form--check">
                                        <input class="form-check-input" type="checkbox" id="agree"
                                            @checked(old('agree')) name="agree" required>
                                        <label class="form-check-label" for="agree">
                                            @lang('I agree with')
                                            @foreach ($policyPages as $policy)
                                                <a href="{{ route('policy.pages', $policy->slug) }}"
                                                    target="_blank">{{ __($policy->data_values->title) }}</a>
                                                @if (!$loop->last)
                                                    ,
                                                @endif
                                            @endforeach
                                        </label>
                                    </div>
                                </div>
                            </div>
                        @endif
                        <div class="col-sm-12">
                            <button type="submit" id="recaptcha"
                                class="btn btn--base btn--lg w-100">@lang('Create account')</button>
                        </div>
                    </div>

                    @include($activeTemplate . 'user.auth.social_login', ['action' => 'Register'])
                </div>
            </form>
        </div>
    </section>

    <div class="modal custom--modal fade" id="existModalCenter" tabindex="-1" role="dialog"
        aria-labelledby="existModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title " id="existModalLongTitle">@lang('You are with us')</h5>
                    <span type="button" class="close-icon" data-bs-dismiss="modal" aria-label="Close">
                        <i class="las la-times"></i>
                    </span>
                </div>
                <div class="modal-body">
                    <p class="text text-center ">@lang('You already have an account please Login ')</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-dark btn--sm"
                        data-bs-dismiss="modal">@lang('Close')</button>
                    <a href="{{ route('user.login') }}" class="btn btn--base btn--sm">@lang('Login')</a>
                </div>
            </div>
        </div>
    </div>
@endsection


@if (gs('secure_password'))
    @push('script-lib')
        <script src="{{ asset('assets/global/js/secure_password.js') }}"></script>
    @endpush
@endif


@push('style')
    <style>
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

@push('script')
    <script>
        "use strict";
        (function($) {

            $('.checkUser').on('focusout', function(e) {
                var url = '{{ route('user.checkUser') }}';
                var value = $(this).val();
                var token = '{{ csrf_token() }}';

                var data = {
                    email: value,
                    _token: token
                }

                $.post(url, data, function(response) {
                    if (response.data != false) {
                        $('#existModalCenter').modal('show');
                    }
                });
            });
        })(jQuery);
    </script>
@endpush

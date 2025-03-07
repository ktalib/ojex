@extends($activeTemplate . 'layouts.master')

@section('content')
    <div class="container">
        <div class="row justify-content-center mt-4">
            <div class="col-md-8">

                <div class="card custom--card">
                    <div class="card-header">
                        <h5 class="card-title">@lang('Change Password')</h5>
                    </div>
                    <div class="card-body">
                        <form  method="post">
                            @csrf

                            <div class="form-group">
                                <label class="form--label">@lang('Current Password')</label>
                                <div class="position-relative">
                                    <input type="password" class="form-control form--control" name="current_password" required autocomplete="current-password">
                                    <span class="password-show-hide fas fa-eye toggle-password fa-eye-slash" id="#current_password"></span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="form--label">@lang('Password')</label>
                                <div class="position-relative">
                                    <input type="password" class="form-control form--control @if (gs('secure_password')) secure-password @endif" name="password" required autocomplete="current-password">
                                    <span class="password-show-hide fas fa-eye toggle-password fa-eye-slash" id="#password"></span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="form--label">@lang('Confirm Password')</label>
                                <div class="position-relative">
                                    <input type="password" class="form-control form--control" name="password_confirmation" required autocomplete="current-password">
                                    <span class="password-show-hide fas fa-eye toggle-password fa-eye-slash" id="#password_confirmation"></span>
                                </div>
                            </div>
                            <div class="form-group">
                                <button type="submit" class="btn btn--base w-100">@lang('Submit')</button>
                            </div>
                        </form>
                    </div>
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

@php
    $contactContent = getContent('contact.content', true);
    $contactElements = getContent('contact.element', orderById: true);
@endphp

@extends($activeTemplate . 'layouts.frontend')
@section('content')

    @include($activeTemplate . 'partials.breadcrumb')
    <!--================= contact section start here ================= -->
    <section class="contact-section py-120">
        <div class="shape-one"></div>
        <div class="shape-two"></div>
        <div class="container">
            <div class="row gy-4">
                @foreach ($contactElements as $contactElement)
                    <div class="col-xl-3 col-sm-6">
                        <div class="contact-item">
                            <span class="contact-item__icon">
                                @php  echo $contactElement->data_values->icon @endphp
                            </span>
                            <h6 class="contact-item__title">
                                {{ __(@$contactElement->data_values->heading) }}
                            </h6>
                            <div class="contact-item__content">
                                <p class="contact-item__desc">
                                    {{ __(@$contactElement->data_values->subheading) }}
                                </p>

                                @php
                                    $contactTypes = ['mailto', 'tel', 'skype'];
                                @endphp

                                @if (in_array($contactElement->data_values->contact_type, $contactTypes))
                                    <a href="{{ $contactElement->data_values->contact_type }}:{{ @$contactElement->data_values->content }}"
                                        class="contact-item__link">
                                        {{ __(@$contactElement->data_values->content) }} </a>
                                @else
                                    <a href="javascript:void(0)" class="contact-item__link">
                                        {{ __(@$contactElement->data_values->content) }} </a>
                                @endif

                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="contact-bottom pt-120">
                <div class="row gy-4">
                    <div class="col-lg-6">
                        <div class="contact-bottom__map">
                            <iframe src="{{ __(@$contactContent->data_values->map_url) }} }}" width="600" height="450"
                                style="border:0;" allowfullscreen="" loading="lazy"
                                referrerpolicy="no-referrer-when-downgrade"></iframe>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="contact-bottom__form">
                            <h4 class="contact-bottom__form-title">{{ __(@$contactContent->data_values->title) }}</h4>
                            <p class="contact-bottom__form-desc">
                                {{ __(@$contactContent->data_values->description) }}
                            </p>
                            <form method="post" action="contact" class="verify-gcaptcha">
                                @csrf
                                <div class="row">
                                    <div class="col-xl-6 col-lg-12 col-sm-6">
                                        <div class="form-group">
                                            <input name="name" type="text" placeholder="@lang('Your Name')"
                                                class="form-control form--control"
                                                value="{{ old('name', @$user->fullname) }}"
                                                @if ($user && $user->profile_complete) readonly @endif required>
                                        </div>
                                    </div>
                                    <div class="col-xl-6 col-lg-12 col-sm-6">
                                        <div class="form-group">
                                            <input name="email" placeholder="@lang('Email Address')" type="email"
                                                class="form-control form--control"
                                                value="{{ old('email', @$user->email) }}"
                                                @if ($user) readonly @endif required>
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <input name="subject" placeholder="@lang('Subject')" type="text"
                                                class="form-control form--control" value="{{ old('subject') }}" required>
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <textarea name="message" placeholder="@lang('Message')" class="form-control form--control" required>{{ old('message') }}</textarea>
                                        </div>
                                    </div>
                                    <x-captcha :showLabel="false" />
                                    <div class="col-sm-12">
                                        <button class="btn btn--base btn--lg w-100">@lang('Submit')</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    @if (@$sections->secs != null)
        @foreach (json_decode($sections->secs) as $sec)
            @include($activeTemplate . 'sections.' . $sec)
        @endforeach
    @endif

@endsection

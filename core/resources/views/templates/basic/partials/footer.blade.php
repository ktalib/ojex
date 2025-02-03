@php
    $footerContent = getContent('footer.content', true);
    $subscribeContent = getContent('subscribe.content', true);
    $policyPages = getContent('policy_pages.element');
    $socialElements = getContent('social_icon.element', orderById: true);
@endphp

<footer class="footer-area section-bg-two pt-120">
    <div class="shape-one"></div>
    <div class="pb-60 footer-area__wrapper">
        <div class="container">
            <div class="row justify-content-center gy-5">
                <div class="col-xl-4 col-sm-6">
                    <div class="footer-item">
                        <div class="footer-item__logo">
                            <a href="{{ route('home') }}"> <img src="{{ siteLogo() }}" alt=""></a>
                        </div>
                        <p class="footer-item__desc">
                            {{ @$footerContent->data_values->details }}
                        </p>
                    </div>
                </div>
                <div class="col-xl-2 col-sm-6">
                    <div class="footer-item">
                        <h6 class="footer-item__title">@lang('QUICK LINKS')</h6>
                        <ul class="footer-menu">
                            <li class="footer-menu__item">
                                <a href="{{ route('trade') }}" class="footer-menu__link">@lang('Trade')</a>
                            </li>
                            <li class="footer-menu__item">
                                <a href="{{ route('market') }}" class="footer-menu__link">@lang('Market')</a>
                            </li>
                            <li class="footer-menu__item">
                                <a href="{{ route('crypto.currencies') }}" class="footer-menu__link">@lang('Crypto Currency')</a>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="col-xl-3 col-sm-6">
                    <div class="footer-item">
                        <h6 class="footer-item__title">@lang('LEGAL')</h6>
                        <ul class="footer-menu">
                            @if (gs('agree'))
                                @foreach ($policyPages as $policy)
                                    <li class="footer-menu__item">
                                        <a href="{{ route('policy.pages', $policy->slug) }} " class="footer-menu__link">{{ __($policy->data_values->title) }} </a>
                                    </li>
                                @endforeach
                            @endif
                        </ul>
                    </div>
                </div>
                <div class="col-xl-3 col-sm-6">
                    <div class="footer-item">
                        <div class="footer-item__wrapper">
                            <h6 class="footer-item__title mb-0">@lang('Follow Us')</h6>
                            <ul class="social-list mb-0">
                                @foreach ($socialElements as $element)
                                    <li class="social-list__item">
                                        <a href="{{ @$element->data_values->url }}" target="_blank" class="social-list__link flex-center">
                                            @php echo @$element->data_values->social_icon @endphp
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                        <div class="footer-contact">
                            <div class="footer-contact__item">
                                <h6 class="footer-contact__title">{{ __(@$subscribeContent->data_values->heading) }}
                                </h6>
                                <p class="footer-contact__desc"> {{ __(@$subscribeContent->data_values->subheading) }}
                                </p>
                            </div>
                            <form action="#" id="subscribe-form" class="position-relative">
                                <input type="email" class="form--control form-control" name="email" placeholder="@lang('Email Address')">
                                <button type="submit" class="btn btn--base subscribe-btn btn--sm">
                                    {{ __(@$subscribeContent->data_values->button_text) }} </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Footer Top End-->

    <!-- bottom Footer -->
    <div class="bottom-footer py-3">
        <div class="container">
            <div class="text-center">
                <p class="bottom-footer__text text-white">@php echo copyRightText(); @endphp</p>
            </div>
        </div>
    </div>
</footer>


@push('script')
    <script>
        "use strict";
        (function($) {
            $('#subscribe-form').on('submit', function(e) {
                e.preventDefault();
                let formData = new FormData($(this)[0]);
                let $this = $(this);
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': "{{ csrf_token() }}"
                    },
                    url: "{{ route('subscribe') }}",
                    method: "POST",
                    data: formData,
                    cache: false,
                    contentType: false,
                    processData: false,
                    beforeSend: function() {
                        $this.find('button[type=submit]').html(`
                        <span class="right-sidebar__button-icon">
                            <i class="las la-spinner la-spin"></i>
                        </span>`).attr('disabled', true);
                    },
                    complete: function(e) {
                        $this.find('button[type=submit]').html(`{{ __(@$subscribeContent->data_values->button_text) }}`).attr('disabled', false);
                    },
                    success: function(resp) {
                        if (resp.success) {
                            $('[name=email]').val('');
                            notify('success', resp.message);
                        } else {
                            notify('error', resp.message || resp.error);
                        }
                    }
                });
            });
        })(jQuery);
    </script>
@endpush

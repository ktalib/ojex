@php
    $ctaContent = getContent('cta.content', true);
@endphp
<section class="cta-section">
    <div class="container">
        <div class="cta">
            <h2 class="cta__title" s-break="-5">{{ __(@$ctaContent->data_values->heading) }}</h2>
            <div class="cta__btn">
                <a href="{{ @$ctaContent->data_values->button_one_url }}" class="btn btn--base">
                    {{ __(@$ctaContent->data_values->button_one_text) }} </a>
                <a
                    href="{{ @$ctaContent->data_values->button_two_url }}" class="btn btn-outline--base">{{ __(@$ctaContent->data_values->button_two_text) }}</a>
            </div>
        </div>
    </div>
</section>

@push('script')
    <script>
        (function($) {
            'use strict';
            $(".cta-section").siblings(`section`).last().addClass(`is-cta-up is-cta`);
            $("footer").addClass(`is-cta`);
        })(jQuery);
    </script>
@endpush

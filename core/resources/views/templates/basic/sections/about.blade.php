@php
    $aboutContent = getContent('about.content', true);
@endphp
<section class="about-section py-120">
    <div class="container">
        <div class="row gy-4 justify-content-between align-items-center">
            <div class="col-md-6 pe-md-5">
                <div class="section-heading style-left">
                    <h2 class="section-heading__title" s-break="-2">{{ __(@$aboutContent->data_values->heading) }} </h2>
                </div>
                <div class="about-section__content">
                    <p class="desc">{{ __(@$aboutContent->data_values->description) }}</p>
                    <a href="{{ url(@$aboutContent->data_values->button_url) }}"
                        class="btn btn--base">{{ __(@$aboutContent->data_values->button_text) }} </a>
                </div>
            </div>
            <div class="col-md-6 col-lg-5">
                <div class="about-thumb">
                    <img src="{{ frontendImage('about', @$aboutContent->data_values->image, '620x660') }}"
                        alt="">
                </div>
            </div>
        </div>
    </div>
</section>

@push('style')
    <style>
        .about-section .section-heading {
            margin-bottom: 32px !important;
        }
    </style>
@endpush

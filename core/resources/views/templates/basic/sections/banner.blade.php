@php
    $bannerContent = getContent('banner.content', true);
@endphp

<section class="banner-section bg-img"
    data-background-image="{{ getImage($activeTemplateTrue . '/images/shapes/banner-bg.png') }}">
    <div class="banner-shap-bg">
        <div class="shape-one-bg"></div>
        <div class="shape-two-bg"></div>
        <div class="shape-three-bg"></div>
        <div class="shape-four-bg"></div>
    </div>
    <div class="banner-shape">
        <div class="banner-shape__one">
            <img src="{{ frontendImage('banner', @$bannerContent->data_values->image_one, '75x75') }}"
                alt="@lang('banner image')">
        </div>
        <div class="banner-shape__two">
            <img src="{{ frontendImage('banner', @$bannerContent->data_values->image_two, '75x75') }}"
                alt="@lang('banner image')">
        </div>
        <div class="banner-shape__three">
            <img src="{{ frontendImage('banner', @$bannerContent->data_values->image_three, '75x75') }}"
                alt="@lang('banner image')">
        </div>
        <div class="banner-shape__four">
            <img src="{{ frontendImage('banner', @$bannerContent->data_values->image_four, '75x75') }}"
                alt="@lang('banner image')">
        </div>
    </div>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-12">
                <div class="banner-content">
                    <h4 class="banner-content__subtitle"> {{ __(@$bannerContent->data_values->title) }} </h4>
                    <h1 class="banner-content__title" s-break="-2">{{ __(@$bannerContent->data_values->heading) }}</h1>
                    <p class="banner-content__desc">
                        {{ __(@$bannerContent->data_values->subheading) }}
                    </p>
                    <div class="banner-content__button">
                        <a href="{{ @$bannerContent->data_values->button_one_url }}" class="btn btn--base">
                            {{ __(@$bannerContent->data_values->button_one_text) }} </a>
                        <a href="{{ @$bannerContent->data_values->button_two_url }}" class="btn btn-outline--base">
                            <span class="icon">@php echo @$bannerContent->data_values->button_two_icon  @endphp</span>
                            {{ __(@$bannerContent->data_values->button_two_text) }} </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>


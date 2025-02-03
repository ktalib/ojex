@php
    $counterContent = getContent('counter.content', true);
    $counterElements = getContent('counter.element', orderById: true);
@endphp

<section class="counter-up-section py-120 section-bg-two">
    <div class="shape-one"></div>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-9">
                <div class="section-heading">
                    <h2 class="section-heading__title" s-break="-2">{{ __(@$counterContent->data_values->heading) }}
                    </h2>
                    <p class="section-heading__desc"> {{ __(@$counterContent->data_values->subheading) }} </p>
                </div>
                <div class="row gy-4">
                    @foreach ($counterElements as $counterElement)
                        <div class="col-6 col-sm-3">
                            <div class="counterup-item ">
                                <div class="counterup-item__content">
                                    <div class="content">
                                        <div class="counterup-item__number">
                                            <h3 class="counterup-item__title title-style"><span class="odometer"
                                                    data-odometer-final="{{ $counterElement->data_values->counter_digit }}"></span>{{ $counterElement->data_values->symbol }}
                                            </h3>
                                        </div>
                                        <span
                                            class="counterup-item__text mb-0">{{ __(@$counterElement->data_values->title) }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>

@push('style-lib')
    <link rel="stylesheet" href="{{ asset($activeTemplateTrue . 'css/odometer.css') }}">
@endpush

@push('script-lib')
    <script src="{{ asset($activeTemplateTrue . 'js/odometer.min.js') }}"></script>
@endpush

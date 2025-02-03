@php
    $testimonialContent = getContent('testimonial.content', true);
    $testimonialElements = getContent('testimonial.element', orderById: true);
@endphp
<section class="testimonial-section py-120">
    <div class="shape-one"></div>
    <div class="container">
        <div class="section-heading">
            <span class="section-heading__subtitle">{{ __(@$testimonialContent->data_values->title) }}</span>
            <h2 class="section-heading__title" s-break="-3">{{ __(@$testimonialContent->data_values->heading) }}</h2>
        </div>
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="testimonial-wrapper">
                    <div class="testimonial-slider">
                        @foreach ($testimonialElements as $testimonialElement)
                            <div class="testimonails-card">
                                <div class="testimonial-item">
                                    <div class="testimonial-item__content">
                                        <div class="testimonial-item__rating">
                                            <ul class="rating-list">
                                                @for ($i = 0; $i < $testimonialElement->data_values->rating; $i++)
                                                    <li class="rating-list__item"><i class="las la-star"></i></li>
                                                @endfor
                                            </ul>
                                        </div>
                                    </div>
                                    <p class="testimonial-item__desc">
                                        {{ __(@$testimonialElement->data_values->quote) }}
                                    </p>
                                    <div class="testimonial-item__info">
                                        <div class="testimonial-item__thumb">
                                            <img src="{{ frontendImage('testimonial', @$testimonialElement->data_values->image, '60x60') }}"
                                                class="fit-image" alt="">
                                        </div>
                                        <div class="testimonial-item__details">
                                            <h6 class="testimonial-item__name">
                                                {{ __(@$testimonialElement->data_values->name) }}
                                            </h6>
                                            <span
                                                class="testimonial-item__designation">{{ __(@$testimonialElement->data_values->designation) }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

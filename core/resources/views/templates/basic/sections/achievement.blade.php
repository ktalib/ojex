@php
    $achievementContent = getContent('achievement.content', true);
    $achievementElements = getContent('achievement.element',orderById: true);
@endphp
<section class="achievement-section py-120">
    <div class="shape-one"></div>
    <div class="container">
        <div class="row gy-4">
            <div class="col-lg-6 pe-lg-5">
                <div class="achievement-left">
                    <div class="section-heading style-left">
                        <span class="section-heading__subtitle">
                            {{ __(@$achievementContent->data_values->title) }}
                        </span>
                        <h2 class="section-heading__title" s-break="-2">{{ __(@$achievementContent->data_values->heading) }}</h2>
                        <p class="section-heading__desc">
                            {{ __(@$achievementContent->data_values->subheading) }}
                        </p>
                    </div>
                    <div class="achievement-left__review">
                        <h6 class="review-title"> {{ __(@$achievementContent->data_values->review_title) }} </h6>
                        <div class="achievement__rating">
                            <ul class="rating-list">
                                @for ($i = 0; $i < @$achievementContent->data_values->rating; $i++)
                                    <li class="rating-list__item"><i class="las la-star"></i></li>
                                @endfor
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 ps-lg-5">
                <div class="achievement-right">
                    @foreach ($achievementElements as $element)
                        <div class="content">
                            <h4 class="title">{{ __(@$element->data_values->heading) }} </h4>
                            <p class="desc">
                                {{ __(@$element->data_values->description) }}
                            </p>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>

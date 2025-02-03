@php
    $howItWorkContent = getContent('how_it_work.content', true);
    $howItWorkElements = getContent('how_it_work.element', orderById: true);
@endphp
<section class="how-work-section py-120">
    <div class="shape-one"></div>
    <div class="container">
        <div class="row justify-content-xl-between gy-5">
            <div class="col-lg-6">
                <div class="section-heading style-left mb-0">
                    <span class="section-heading__subtitle">
                        {{ __(@$howItWorkContent->data_values->title) }}
                    </span>
                    <h2 class="section-heading__title" s-break="-2">{{ __(@$howItWorkContent->data_values->heading) }}
                    </h2>
                    <p class="section-heading__desc"> {{ __(@$howItWorkContent->data_values->subheading) }}</p>
                    <div class="section-heading__btn">
                        <a href="{{ @$howItWorkContent->data_values->button_url }}" class="btn btn--base">
                            {{ __(@$howItWorkContent->data_values->button_text) }}
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 ps-lg-5">
                <ul class="how-work">
                    @foreach ($howItWorkElements as $howItWorkElement)
                        <li class="how-work-item">
                            <span class="how-work-item__icon"> @php echo $howItWorkElement->data_values->icon @endphp </span>
                            <div class="how-work-item__content">
                                <h5 class="how-work-item__title">{{ __(@$howItWorkElement->data_values->heading) }}
                                </h5>
                                <p class="how-work-item__desc">{{ __(@$howItWorkElement->data_values->subheading) }}
                                </p>
                            </div>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
</section>

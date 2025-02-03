@php
    $highlightContent = getContent('highlights.content', true);
    $highlightElements = getContent('highlights.element',orderById:true);
@endphp
<section class="highlight-section py-120">
    <div class="shape-one"></div>
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="section-heading">
                    <span class="section-heading__subtitle">{{ __(@$highlightContent->data_values->title) }} </span>
                    <h2 class="section-heading__title" s-break="-1">{{ __(@$highlightContent->data_values->heading) }}</h2>
                </div>
            </div>
        </div>

        <div class="row gy-4 justify-content-center">
            @foreach ($highlightElements as $highlightElement)
                <div class="col-lg-4 col-sm-6">
                    <div class="highlight-item">
                        <h6 class="highlight-item__title">
                            {{ __(@$highlightElement->data_values->heading) }}
                        </h6>
                        <p class="highlight-item__desc">
                            {{ __(@$highlightElement->data_values->subheading) }}
                        </p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

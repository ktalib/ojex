@php
    $whyChooseUsContent = getContent('why_choose_us.content', true);
    $whyChooseUsElements = getContent('why_choose_us.element', orderById: true);
@endphp
<section class="why-choose-section py-120">
    <div class="shape-one"></div>
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="section-heading">
                    <span class="section-heading__subtitle"> {{ __(@$whyChooseUsContent->data_values->title) }}</span>
                    <h2 class="section-heading__title" s-break="-3">
                        {{ __(@$whyChooseUsContent->data_values->heading) }} </h2>
                    <p class="section-heading__desc">
                        {{ __(@$whyChooseUsContent->data_values->subheading) }}
                    </p>
                </div>
            </div>
        </div>
        <div class="row justify-content-center choose-item-wrapper gy-4">
            @foreach ($whyChooseUsElements as $whyChooseUsElement)
                <div class="col-lg-4 col-sm-6">
                    <div class="choose-item">
                        <div class="choose-item__icon">
                            @php echo @$whyChooseUsElement->data_values->icon @endphp
                        </div>
                        <h5 class="choose-item__title">{{ __(@$whyChooseUsElement->data_values->heading) }} </h5>
                        <p class="choose-item__desc">{{ __(@$whyChooseUsElement->data_values->subheading) }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

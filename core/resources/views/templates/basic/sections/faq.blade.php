@php
    $faqContent = getContent('faq.content', true);
    $faqElements = getContent('faq.element', orderById: true);
@endphp
<section class="faq-section py-120">
    <div class="shape-one"></div>
    <div class="container">
        <div class="row gy-4">
            <div class="col-lg-5 pe-lg-5">
                <div class="section-heading style-left">
                    <span class="section-heading__subtitle"> {{ __(@$faqContent->data_values->title) }} </span>
                    <h2 class="section-heading__title" s-break="-2">{{ __(@$faqContent->data_values->heading) }}</h2>
                    <p class="section-heading__desc">
                        {{ __(@$faqContent->data_values->subheading) }}
                    </p>
                </div>
            </div>
            <div class="col-lg-7">
                <div class="accordion custom--accordion" id="accordionExample">
                    <div>
                        @foreach ($faqElements as $faqElement)
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="heading{{ $loop->index }}">
                                    <button class="accordion-button " type="button" data-bs-toggle="collapse"
                                        data-bs-target="#faq-{{ $loop->index }}" aria-expanded="false">
                                        {{ __(@$faqElement->data_values->question) }}
                                    </button>
                                </h2>
                                <div id="faq-{{ $loop->index }}" class="accordion-collapse collapse"
                                    aria-labelledby="heading{{ $loop->index }}" data-bs-parent="#accordionExample">
                                    <div class="accordion-body">
                                        <p class="text">
                                            {{ __(@$faqElement->data_values->answer) }}
                                        </p>
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

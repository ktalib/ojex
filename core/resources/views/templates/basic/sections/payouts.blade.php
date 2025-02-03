@php
    $payoutContent = getContent('payouts.content',true);
@endphp
<section class="payout-section py-120">
    <div class="shape-one"></div>
    <div class="container">
        <div class="row gy-4 justify-content-between">
            <div class="col-lg-5">
                <div class="payout-thumb">
                    <img src="{{ frontendImage('payouts', @$payoutContent->data_values->image, '475x400') }}" alt="">
                </div>
            </div>
            <div class="col-lg-6">
                <div class="section-heading style-left">
                    <span class="section-heading__subtitle"> {{__(@$payoutContent->data_values->title) }} </span>
                    <h2 class="section-heading__title" s-break="-1">{{__(@$payoutContent->data_values->heading) }}</h2>
                    <p class="section-heading__desc">
                        {{ __(@$payoutContent->data_values->description) }}
                    </p>
                    <div class="section-heading__btn">
                        <a href="{{ @$payoutContent->data_values->button_url }}" class="btn btn--base"> {{__(@$payoutContent->data_values->button_text)}} </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

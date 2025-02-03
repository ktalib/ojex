@php
    $pricingContent = getContent('pricing.content', true);
    $plans = App\Models\Plan::with('planPhases.phaseLogics.logicBox')->active()->get();
@endphp

@if (count($plans))
    <section class="evaluation-section py-120">
        <div class="shape-one"></div>
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="section-heading">
                        <h2 class="section-heading__title" s-break="-3">{{ __(@$pricingContent->data_values->heading) }}</h2>
                        <p class="section-heading__desc">
                            {{ __(@$pricingContent->data_values->subheading) }}
                        </p>
                    </div>
                </div>
            </div>

            @include($activeTemplate . 'partials.plan', ['plans' => $plans])

        </div>
    </section>
@endif

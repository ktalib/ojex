@php
    $planPhaseCounts = $plans
        ->map(function ($plan) {
            return count($plan['planPhases']);
        })
        ->toArray();

    $maxPhase = max($planPhaseCounts);
@endphp
<div class="select-evaluation">
    <div class="evalation-main">
        <div class="evaluation-wrapper" id="evaluation-wrapper">
            <div class="evaluation-wrapper__left">
                <div class="evaluation-card">
                    <div class="evaluation-card__header">
                        <h5 class="evaluation-card__title"> @lang('Evaluation') </h5>
                    </div>
                    <div class="evaluation-card__body">
                        <ul class="evaluation-list">
                            <li class="evaluation-item">@lang('Plan Name') </li>
                            <li class="evaluation-item">@lang('No. of Phase') </li>
                            @for ($i = 0; $i < $maxPhase;)
                                <li class="evaluation-item target-{{ $i }}">@lang('Phase') {{ ++$i }} @lang('Target')</li>
                            @endfor
                            <li class="evaluation-item profit">@lang('Profit Share') </li>
                            <li class="evaluation-item">@lang('Maximum Daily Loss') </li>
                            <li class="evaluation-item">@lang('Maximum Overall Loss') </li>
                            <li class="evaluation-item">@lang('Leverage') </li>
                            <li class="evaluation-item">@lang('Conversion') </li>
                            <li class="evaluation-item">@lang('Registration Fee') </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="evaluation-wrapper__right">
                @foreach ($plans as $key => $plan)
                    <div class="evaluation-card">
                        <div class="evaluation-card__header">
                            <h4 class="evaluation-card__title-two">@lang('Evaluation') </h4>
                            <h5 class="evaluation-card__title">{{ showAmount($plan->fund, 2) }}</h5>
                        </div>
                        <div class="evaluation-card__body">
                            <ul class="evaluation-list">
                                <li class="evaluation-item">
                                    <span class="evaluation-item__name">@lang('Plan Name')</span>
                                    <span class="evaluation-item__text">{{ __($plan->name) }}</span>
                                </li>
                                <li class="evaluation-item">
                                    <span class="evaluation-item__name">@lang('No. of Phase')</span>
                                    <span class="evaluation-item__text">{{ $plan->planPhases->count() }}</span>
                                </li>

                                @for ($currentPhase = 0; $currentPhase < $maxPhase; $currentPhase++)
                                    <li class="evaluation-item target-{{ $currentPhase }}">
                                        <span class="evaluation-item__name">@lang('Phase') {{ $currentPhase + 1 }} @lang('Target')</span>
                                        @if (isset($plan->planPhases[$currentPhase]))
                                            <ul class="evaluation-item-meta">
                                                @foreach ($plan->planPhases[$currentPhase]->phaseLogics as $phaseLogic)
                                                    <li class="evaluation-item-meta__item">
                                                        <span class="evaluation-item__text">{{ $phaseLogic->logicBox->name }}</span>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        @else
                                            <span class="evaluation-item__text">{{ '--' }}</span>
                                        @endif
                                    </li>
                                @endfor

                                <li class="evaluation-item profit">
                                    <span class="evaluation-item__name">@lang('Profit Share')</span>
                                    <span class="evaluation-item__text">
                                        @foreach ($plan->planPhases as $phase)
                                            @lang('P'){{ $loop->iteration }}:
                                            {{ showAmount($phase->profit, exceptZeros: true, currencyFormat: false) }}{{ !$loop->last ? '%,' : '%' }}
                                        @endforeach
                                    </span>
                                </li>
                                <li class="evaluation-item">
                                    <span class="evaluation-item__name">@lang('Maximum Daily Loss')</span>
                                    <span class="evaluation-item__text">{{ showAmount($plan->max_daily_loss, 2, currencyFormat: false) }}%</span>
                                </li>
                                <li class="evaluation-item">
                                    <span class="evaluation-item__name">@lang('Maximum Overall Loss')</span>
                                    <span class="evaluation-item__text">{{ showAmount($plan->max_overall_loss, 2, currencyFormat: false) }}%</span>
                                </li>
                                <li class="evaluation-item">
                                    <span class="evaluation-item__name">@lang('Leverage') </span>
                                    <span class="evaluation-item__text">1:{{ showAmount($plan->fund / $plan->price, 2, exceptZeros: true, currencyFormat: false) }}</span>
                                </li>
                                <li class="evaluation-item">
                                    <span class="evaluation-item__name">@lang('Conversion') </span>
                                    <span class="evaluation-item__text">{{ $plan->conversion }}
                                        @lang('times')</span>
                                </li>
                                <li class="evaluation-item">
                                    <span class="evaluation-item__name">@lang('Registration Fee') </span>
                                    <span class="evaluation-item__text">{{ showAmount($plan->price, 2) }}</span>
                                </li>
                            </ul>
                        </div>
                        <div class="evaluation-card__footer">
                            <a href="{{ route('user.plan.buy', $plan->id) }}" class="btn btn-outline--base w-100" data-plan="{{ $plan }}">@lang('Buy Plan') </a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>


@push('script')
    <script>
        (function($) {
            "use strict";

            function isOverflowing(element) {
                return element.scrollWidth > element.clientWidth || element.scrollHeight > element.clientHeight;
            }

            let element = document.querySelector('.evaluation-wrapper__right');

            if (isOverflowing(element)) {
                element.scrollLeft = 0;
            }

            element.addEventListener('wheel', (event) => {
                let atStart = element.scrollLeft === 0 && event.deltaY < 0;
                let atEnd = element.scrollLeft + element.clientWidth >= element.scrollWidth && event.deltaY > 0;

                if (!atStart && !atEnd) {
                    event.preventDefault();

                    element.scrollBy({
                        left: event.deltaY > 0 ? 325 : -325
                    });
                }
            }, {
                passive: false
            });

            function DynamicallySetItemHeight(items) {
                let evListItemHeights = [];
                let evListItems = items;
                $(evListItems).each((index, item) => evListItemHeights.push(item.clientHeight));

                let evListItemHeightsNew = [...new Set(evListItemHeights)];
                let evListItemMinHeight = Math.min(...evListItemHeightsNew);
                let evListItemMaxHeight = Math.max(...evListItemHeightsNew);

                $(evListItems).each((index, item) => {
                    $(item).css('height', `${evListItemMaxHeight}px`);
                });
            }

            let maxPhase = `{{ $maxPhase }}`;

            for (let index = 0; index < maxPhase; index++) {
                DynamicallySetItemHeight($(`.evaluation-list > li.target-${index}`))
            }

            DynamicallySetItemHeight($('.evaluation-list > li.profit'))

        })(jQuery);
    </script>
@endpush

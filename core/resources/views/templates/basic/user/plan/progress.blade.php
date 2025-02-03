@extends($activeTemplate . 'layouts.master')

@section('content')
    @if ($planHistory)
        <div class="row gy-4">
            <div class="col-12">
                <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
                    <h5 class="mb-0">{{ __($planHistory->plan->name) }}</h5>
                    <form class="max-w-content flex-grow-1">
                        <div class="select2-wrapper">
                            <select class="form--select form--control submit-form-on-change select2" name="plan_history"
                                data-minimum-results-for-search="-1">
                                @foreach ($planHistories as $singleHistory)
                                    <option value="{{ $singleHistory->id }}" @selected(request()->plan_history == $singleHistory->id)>
                                        {{ __($singleHistory->plan->name) }}
                                        ({{ showDateTime($singleHistory->created_at, 'd-M-y') }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </form>
                </div>
            </div>
            <div class="col-12 col-xl-8">
                <div class="dashboard-card">
                    <table class="table table--responsive--md">
                        <thead>
                            <tr>
                                <th width="100px">@lang('Phase No')</th>
                                <th>@lang('Phase Name')</th>
                                <th>@lang('Logic')</th>
                                <th>@lang('Progress')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($planHistory->plan->planPhases as $planPhase)
                                @foreach ($planPhase->phaseLogics as $logic)
                                    <tr>
                                        <td>{{ $loop->parent->iteration }}</td>
                                        <td>{{ __($planPhase->name) }}</td>
                                        <td class="text-wrap">{{ __($logic->logicBox->name) }}</td>
                                        <td><?php echo getPhaseLogicProgress($planHistory, $logic); ?></td>
                                    </tr>
                                @endforeach
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="col-12 col-xl-4">
                <div class="dashboard-card">
                    <table class="table table--responsive--md">
                        <thead>
                            <tr>
                                <th>@lang('Phase Name')</th>
                                <th>@lang('Profit')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($planHistory->plan->planPhases as $planPhase)
                                <tr>
                                    <td>{{ __($planPhase->name) }}</td>
                                    <td>{{ getPhaseProfit($planHistory, $planPhase) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="{{ count($chart['date']) > 3 ? 'col-12' : 'col-xl-8' }}">
                <div class="dashboard-card">
                    <h5 class="dashboard-card__title">@lang('Daily Profit Loss')</h5>
                    <div id="plChartArea"> </div>
                </div>
            </div>
        </div>
    @else
        @if (count($planHistories))
            <div class="d-flex justify-content-end">
                <form>
                    <div class="select2-wrapper">
                        <select class="form--select form--control submit-form-on-change select2" name="plan_history"
                            data-minimum-results-for-search="-1">
                            @foreach ($planHistories as $singleHistory)
                                <option value="{{ $singleHistory->id }}" @selected(request()->plan_history)>
                                    {{ __($singleHistory->plan->name) }}
                                    ({{ showDateTime($singleHistory->created_at, 'd-M-y') }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                </form>
            </div>
        @endif
        <div class="dashboard-card">
            <div class="transection__item justify-content-center p-5 skeleton">
                <div class="empty-thumb text-center">
                    <img src="{{ asset('assets/images/extra_images/empty.png') }}" />
                    <p class="fs-14">@lang('No plan found')</p>
                </div>
            </div>
        </div>
    @endif
@endsection


@if ($planHistory)
    @push('script-lib')
        <script src="{{ asset('assets/global/js/vendor/apexcharts.min.js') }}"></script>
        <script src="{{ asset('assets/global/js/vendor/chart.js.2.8.0.js') }}"></script>
    @endpush

    @push('style')
        <style>
            .dashboard-card__title {
                border-bottom: 1px solid hsl(var(--white) / 0.12);
                padding-bottom: 16px;
                margin-bottom: 16px;
            }
            .select2-container .select2-selection--single .select2-selection__arrow {
                position: relative;
                top: 0;
                right: 0;
                transform: none;
            }

            .select2-container .select2-selection--single .select2-selection__rendered {
                padding: 0 !important;
            }

            .select2-container .select2-selection--single, 
            .select2-container .select2-selection--mutiple {
                display: flex;
                align-items: center;
                gap: 8px;
                padding: 20px 24px !important;
            }
        </style>
    @endpush

    @push('script')
        <script>
            (function($) {
                "use strict";

                var options = {
                    series: [{
                        name: 'Profit Loss',
                        data: @json($chart['amount'])
                    }],
                    chart: {
                        type: 'bar',
                        height: 650,
                        toolbar: {
                            show: false
                        }
                    },
                    plotOptions: {
                        bar: {
                            horizontal: false,
                            columnWidth: '50%',
                            endingShape: 'rounded',
                            colors: {
                                ranges: [{
                                        from: -Infinity,
                                        to: 0,
                                        color: '#fb4646'
                                    },
                                    {
                                        from: 0,
                                        to: Infinity,
                                        color: '#7ac851'
                                    }
                                ]
                            }
                        },
                    },
                    dataLabels: {
                        enabled: false
                    },
                    stroke: {
                        show: true,
                        width: 2,
                        colors: ['transparent']
                    },
                    xaxis: {
                        categories: @json($chart['date']),
                        labels: {
                            style: {
                                colors: '#FFFFFF',
                                fontSize: '14px'
                            }
                        }
                    },
                    yaxis: {
                        title: {
                            text: "{{ __(gs('cur_sym')) }}",
                        },
                        labels: {
                            formatter: function(value) {
                                return Math.round(value);
                            },
                            style: {
                                colors: '#FFFFFF',
                                fontSize: '14px'
                            }
                        }
                    },
                    grid: {
                        xaxis: {
                            lines: {
                                show: false
                            }
                        },
                        yaxis: {
                            lines: {
                                show: false
                            }
                        },
                    },
                    fill: {
                        opacity: 1
                    },
                    tooltip: {
                        theme: 'dark',
                        y: {
                            formatter: function(val) {
                                return "{{ __(gs('cur_sym')) }}" + val + " "
                            }
                        }
                    }
                };
                var chart = new ApexCharts(document.querySelector("#plChartArea"), options);
                chart.render();

            })(jQuery);
        </script>
    @endpush
@endif

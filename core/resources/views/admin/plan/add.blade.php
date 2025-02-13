@extends('admin.layouts.app')

@section('panel')
    <form action="{{ route('admin.plan.save', @$plan->id) }}" method="POST">
        @csrf
        <div class="row">
            <div class="col-lg-12">
                <div class="card mb-4">
                    <div class="card-header">
                        <div class="d-flex flex-wrap justify-content-between">
                            <h5 class="title">{{ __($pageTitle) }}</h5>
                            <button class="btn btn--dark btn--sm addPhase" type="button"> <i class="las la-plus"></i> @lang('Add Phase')</button>
                        </div>
                    </div>
                    <div class="card-body" style="display: none">
                        <div class="row">
                            <div class="col-md-6 col-xl-3">
                                <div class="form-group">
                                    <label>@lang('Name')</label>
                                    <input type="text" name="name" value="{{ old('name', @$plan->name) }}" class="form-control" required>
                                </div>
                            </div>
                            <div class="col-md-6 col-xl-3">
                                <div class="form-group">
                                    <label>@lang('Price')</label>
                                    <div class="input-group">
                                        <input type="number" step="any" min="0" name="price" value="{{ old('price', @$plan ? getAmount(@$plan->price) : '') }}" class="form-control" required>
                                        <span class="input-group-text">{{ __(gs('cur_text')) }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 col-xl-3">
                                <div class="form-group">
                                    <label>@lang('Fund')</label>
                                    <div class="input-group">
                                        <input type="number" step="any" min="0" name="fund" value="{{ old('fund', getAmount(@$plan->fund)) }}" class="form-control" required>
                                        <span class="input-group-text">{{ __(gs('cur_text')) }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 col-xl-3">
                                <div class="form-group">
                                    <label>@lang('Conversion')</label>
                                    <div class="input-group">
                                        <input type="number" step="1" name="conversion" value="{{ old('conversion', @$plan->conversion) }}" class="form-control" required>
                                        <span class="input-group-text">@lang('Times')</span>
                                    </div>
                                    <small class="text--pink text--small">@lang('How many exchanges are allowed between wallets?')</small>
                                </div>
                            </div>
                            <div class="col-md-6 col-xl-3">
                                <div class="form-group">
                                    <label>@lang('Maximum Daily Loss')</label>
                                    <div class="input-group">
                                        <input type="number" step="any" min="0" name="max_daily_loss" value="{{ getAmount(@$plan->max_daily_loss) }}" class="form-control" required>
                                        <span class="input-group-text">%</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 col-xl-3">
                                <div class="form-group">
                                    <label>@lang('Maximum Overall Loss')</label>
                                    <div class="input-group">
                                        <input type="number" step="any" min="0" name="max_overall_loss" value="{{ getAmount(@$plan->max_overall_loss) }}" class="form-control" required>
                                        <span class="input-group-text">%</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="planPhase">
                    @foreach (@$plan->planPhases ?? [] as $phase)
                        <div class="card mb-3" data-phase_count="{{ $loop->iteration }}">
                            <div class="card-header">
                                <div class="card-header">
                                    <div class="d-flex flex-wrap justify-content-between">
                                        <h5 class="title">{{ __($phase->name) }}</h5>
                                        <div>
                                            <button class="btn btn--dark btn--sm addLogin" type="button"> <i class="las la-plus"></i> @lang('Add Logic')</button>
                                            <button class="btn btn--danger btn--sm removePhaseBtn" type="button"> <i class="las la-trash"></i> @lang('Delete Phase')</button>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <input type="hidden" name="old_phase[{{ $loop->iteration }}]" value="{{ $phase->id }}">
                                    <div class="row phaseLogic">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>@lang('Phase Name')</label>
                                                <input type="text" name="phase_name[{{ $loop->iteration }}]" value="{{ old("phase_name[$loop->iteration]", $phase->name) }}" class="form-control" required>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>@lang('Profit Share')</label>
                                                <div class="input-group">
                                                    <input type="number" step="any" name="phase_profit[{{ $loop->iteration }}]" value="{{ old("phase_profit[$loop->iteration]", getAmount($phase->profit)) }}" class="form-control" required>
                                                    <span class="input-group-text">%</span>
                                                </div>
                                            </div>
                                        </div>

                                        @foreach ($phase->phaseLogics as $logic)
                                            <div class="col-md-6 logicBox">
                                                <input type="hidden" name="old_logic[{{ $loop->parent->iteration }}][{{ $loop->iteration }}]" value="{{ $logic->id }}">
                                                <div class="form-group">
                                                    <label>@lang('Logic')</label>
                                                    <div class="d-flex">
                                                        <select name="logic[{{ $loop->parent->iteration }}][{{ $loop->iteration }}]" class="form-control select2-basic" required>
                                                            <option hidden value="">@lang('Select One')</option>
                                                            @foreach ($logicBoxes as $logicBox)
                                                                <option value="{{ $logicBox->id }}" @selected($logicBox->id == $logic->logic_box_id)>{{ __($logicBox->name) }}</option>
                                                            @endforeach
                                                        </select>
                                                        <button class="btn btn--danger btn--sm ms-2 removeLogicBox" type="button"><i class="las la-trash m-0"></i></button>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="card">
                    <div class="card-footer">
                        <button class="btn btn--primary h-45 w-100">@lang('Submit')</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection

@push('breadcrumb-plugins')
    <x-back route="{{ route('admin.plan.list') }}" />
@endpush

@push('script')
    <script>
        (function($) {
            "use strict";

            let phaseCount = `{{ @$plan->planPhases?->count() ?? 0 }}`;

            $('.addPhase').on('click', function() {
                let phaseHtml = `<div class="card mb-3" data-phase_count="${++phaseCount}">
                                    <div class="card-header">
                                        <div class="card-header">
                                            <div class="d-flex flex-wrap justify-content-between">
                                                <h5 class="title">@lang('New Phase')</h5>
                                                <div>
                                                    <button class="btn btn--dark btn--sm addLogin" type="button"> <i class="las la-plus"></i> @lang('Add Logic')</button>
                                                    <button class="btn btn--danger btn--sm removePhaseBtn" type="button"> <i class="las la-trash"></i> @lang('Delete Phase')</button>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-body cardBody-${phaseCount}">
                                            <div class="row phaseLogic">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="required">@lang('Phase Name')</label>
                                                        <input type="text" name="phase_name[${phaseCount}]" class="form-control" required>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="required">@lang('Profit Share')</label>
                                                        <div class="input-group">
                                                            <input type="number" step="any" name="phase_profit[${phaseCount}]" class="form-control" required>
                                                            <span class="input-group-text">%</span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6 logicBox">
                                                    <div class="form-group">
                                                        <label class="required">@lang('Logic')</label>
                                                        <select name="logic[${phaseCount}][1]" class="form-control select2-basic" required>
                                                            <option hidden value="">@lang('Select One')</option>
                                                            @foreach ($logicBoxes as $logicBox)
                                                                <option value="{{ $logicBox->id }}">{{ __($logicBox->name) }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>`;

                $('.planPhase').append(phaseHtml);
                $('html, body').animate({
                    scrollTop: $(document).height()
                }, 'slow');

                $('.select2-basic').select2({
                    dropdownParent: $(`.cardBody-${phaseCount}`)
                });
            });

            $(document).on('click', '.removePhaseBtn', function() {
                $(this).closest('.card').remove();
            });

            function updateAvailableOptions($phase) {
                let selectedIds = $phase.find('select[name^="logic["]').map(function() {
                    return $(this).val();
                }).get().filter(id => id);

                $phase.find('select[name^="logic["]').each(function() {
                    let $select = $(this);
                    let currentValue = $select.val();

                    $select.find('option').each(function() {
                        let $option = $(this);
                        let optionValue = $option.val();

                        if (selectedIds.includes(optionValue) && optionValue !== currentValue) {
                            $option.hide();
                        } else {
                            $option.show();
                        }
                    });

                    $select.select2();
                });
            }

            $('.planPhase .card').each(function() {
                updateAvailableOptions($(this));
            });

            $(document).on('click', '.addLogin', function() {
                let $card = $(this).closest('.card');
                let currentPhase = $card.data('phase_count');
                let alreadyAddedPhase = $card.find('.logicBox').length + 1;

                let selectedLogicIds = $card.find('select[name^="logic["]').map(function() {
                    return $(this).val();
                }).get().filter(id => id); 

                let optionsHtml = '<option hidden value="">@lang('Select One')</option>';
                @foreach ($logicBoxes as $logicBox)
                    if (!selectedLogicIds.includes("{{ $logicBox->id }}")) {
                        optionsHtml += `<option value="{{ $logicBox->id }}">{{ __($logicBox->name) }}</option>`;
                    }
                @endforeach

                let logicHtml = `<div class="col-md-6 logicBox logicBox-${currentPhase}-${alreadyAddedPhase}">
                        <div class="form-group">
                            <label>@lang('Logic')</label>
                            <div class="d-flex">
                                <select name="logic[${currentPhase}][${alreadyAddedPhase}]" class="form-control select2-basic" required>
                                    ${optionsHtml}
                                </select>
                                <button class="btn btn--danger btn--sm ms-2 removeLogicBox" type="button"><i class="las la-trash m-0"></i></button>
                            </div>
                        </div>
                    </div>`;

                $card.find('.phaseLogic').append(logicHtml);

                $(`.logicBox-${currentPhase}-${alreadyAddedPhase} .select2-basic`).select2({
                    dropdownParent: $(`.logicBox-${currentPhase}-${alreadyAddedPhase}`)
                });

                updateAvailableOptions($card);
            });

            $(document).on('click', '.removeLogicBox', function() {
                let $card = $(this).closest('.card');
                $(this).closest('.logicBox').remove();
                updateAvailableOptions($card);
            });

        })(jQuery);
    </script>
@endpush

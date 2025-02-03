@extends('admin.layouts.app')
@section('panel')
    <div class="row">
        <div class="col-lg-12">
            <div class="card b-radius--10">
                <div class="card-body p-0">
                    <div class="table-responsive--md table-responsive">
                        <table class="table--light style--two table">
                            <thead>
                                <tr>
                                    <th>@lang('SL')</th>
                                    <th>@lang('Name')</th>
                                    <th>@lang('Type')</th>
                                    <th>@lang('Duration')</th>
                                    <th>@lang('Status')</th>
                                    <th>@lang('Action')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($logicBoxes as $logicBox)
                                    <tr>
                                        <td>
                                            {{ $logicBoxes->firstItem() + $loop->index }}
                                        </td>
                                        <td>
                                            {{ $logicBox->name }}
                                        </td>
                                        <td>
                                            @if ($logicBox->type == Status::LOGIC_BOX_TYPE_PROFIT)
                                                <span class="badge badge--success">@lang('Profit')</span>
                                            @else
                                                <span class="badge badge--warning">@lang('Loss')</span>
                                            @endif
                                        </td>
                                        
                                        <td>{{ $logicBox->duration }}</td>
                                        <td>
                                            @php
                                                echo $logicBox->statusBadge;
                                            @endphp
                                        </td>
                                        <td>
                                            <div class="button--group">
                                                <button class="btn btn-sm cuModalBtn btn-outline--primary editBtn" data-resource="{{ $logicBox }}" data-modal_title="@lang('Update Logic Box')" type="button">
                                                    <i class="la la-pencil"></i>@lang('Edit')
                                                </button>
                                                @if ($logicBox->status == Status::DISABLE)
                                                    <button class="btn btn-sm btn-outline--success confirmationBtn" data-question="@lang('Are you sure to enable this plan')?" data-action="{{ route('admin.plan.logic.box.status', $logicBox->id) }}">
                                                        <i class="la la-eye"></i> @lang('Enable')
                                                    </button>
                                                @else
                                                    <button class="btn btn-sm btn-outline--danger confirmationBtn" data-question="@lang('Are you sure to disable this plan')?" data-action="{{ route('admin.plan.logic.box.status', $logicBox->id) }}">
                                                        <i class="la la-eye-slash"></i> @lang('Disable')
                                                    </button>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td class="text-muted text-center" colspan="100%">{{ __($emptyMessage) }}</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                @if ($logicBoxes->hasPages())
                    <div class="card-footer py-4">
                        {{ paginateLinks($logicBoxes) }}
                    </div>
                @endif
            </div>
        </div>
    </div>
    <!--Cu Modal -->
    <div class="modal fade" id="cuModal" role="dialog" tabindex="-1">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"></h5>
                    <button class="close" data-bs-dismiss="modal" type="button" aria-label="Close">
                        <i class="las la-times"></i>
                    </button>
                </div>

                <form action="{{ route('admin.plan.logic.box.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label>@lang('Name')</label>
                            <input class="form-control" name="name" type="text" required>
                        </div>
                        <div class="form-group">
                            <label>@lang('Type')</label>
                            <div class="input-group">
                                <select class="form-control" name="type" required>
                                    <option value="1">@lang('Profit')</option>
                                    <option value="2">@lang('Loss')</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>@lang('Logic')</label>
                            <div class="input-group">
                                <select class="form-control" name="logic" required>
                                    @foreach (logicTypes() as $key => $value)
                                        <option value="{{ $key }}">{{ $value }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="value-from form-group">
                            <label>@lang('Value From')</label>
                            <div class="input-group">
                                <input class="form-control" name="value_from" type="number" step="any" required>
                                <span class="input-group-text">%</span>
                            </div>
                        </div>
                        <div class="value-to d-none form-group">
                            <label>@lang('Value To')</label>
                            <div class="input-group">
                                <input class="form-control" name="value_to" type="number" step="any">
                                <span class="input-group-text">%</span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>@lang('Duration')</label>
                            <div class="input-group">
                                <input class="form-control" name="duration" type="number" required>
                                <div class="input-group-text">
                                    @lang('Days')
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn--primary w-100 h-45" type="submit">@lang('Submit')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <x-confirmation-modal />
@endsection

@push('breadcrumb-plugins')
    <div class="d-flex justify-content-between flex-wrap gap-2">
        <x-search-form placeholder="Logic box name" />
        <button class="btn btn-outline--primary cuModalBtn" data-modal_title="@lang('Add New Logic Box')" type="button">
            <i class="las la-plus"></i>@lang('Add new')
        </button>
    </div>
@endpush

@push('script-lib')
    <script src="{{ asset('assets/admin/js/cu-modal.js') }}"></script>
@endpush

@push('script')
    <script>
        (function($) {
            "use strict";
            $('[name="logic"]').on('change', function() {
                var val = $(this).val();
                updateValueHtml(val);
            }).change();

            $('.editBtn').on('click', function() {
                let logicBox = $(this).data('resource');
                updateValueHtml(logicBox.logic);
            });

            function updateValueHtml(val) {
                if (val == 6) {
                    $('.value-from label').text("Value From");
                    $('.value-to').removeClass("d-none");
                    $('.value-to input').attr("required", "required").attr('disabled', false);
                } else {
                    $('.value-from label').text("Value");
                    $('.value-to').addClass("d-none");
                    $('.value-to input').removeAttr("required").attr('disabled', true);;
                }
            }


        })(jQuery)
    </script>
@endpush

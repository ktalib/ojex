@extends('admin.layouts.app')
@section('panel')
<div class="row">

    <div class="col-lg-12">
        <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#addModal">@lang('Add New Plan')</button> 
        <div class="card">
            <div class="card-body p-0">
                <div class="table-responsive--sm table-responsive">
                    <table class="table table--light style--two">
                        <thead>
                        <tr>
                        
                            <th>@lang('Name')</th>
                            
                            <th>@lang('Min')</th>
                            <th>@lang('Max')</th>
                            <th>@lang('Duration Days')</th>
                            <th>@lang('ROI')</th>
                             
                           <th>@lang('Action')</th>

                        </tr>
                        </thead>
                        <tbody>
                        @forelse($user_subscription_plans as $subscriber)
                            <tr>
                     
                                <td>{{ $subscriber->name }}</td>
                                
                                <td>{{ $subscriber->minimum_amount }}</td>
                                <td>{{ $subscriber->maximum_amount }}</td>
                                <td>{{ $subscriber->duration_days }}</td>
                                <td>{{ $subscriber->roi_percentage }}</td>
                                
                                
                                <td>
                                    <button class="btn btn-sm btn-primary" onclick="openUpdateModal({{ json_encode($subscriber) }})">@lang('Update')</button>
                                </td>
                                
                            </tr>
                        @empty
                            <tr>
                                <td class="text-muted text-center" colspan="100%">{{ __($emptyMessage) }}</td>
                            </tr>
                        @endforelse

                        </tbody>
                    </table><!-- table end -->
                </div>
            </div>
            @if ($user_subscription_plans->hasPages())
            <div class="card-footer py-4">
                {{ paginateLinks($subscribers) }}
            </div>
            @endif
        </div><!-- card end -->
    </div>

<!-- Add Plan Modal -->
<div id="addModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="addModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="{{ route('admin.subscriber.subscribe.storePlan') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="addModalLabel">@lang('Add New Plan')</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="name">@lang('Name')</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                    <div class="form-group">
                        <label for="minimum_amount">@lang('Minimum Amount')</label>
                        <input type="number" class="form-control" id="minimum_amount" name="minimum_amount" required>
                    </div>
                    <div class="form-group">
                        <label for="maximum_amount">@lang('Maximum Amount')</label>
                        <input type="number" class="form-control" id="maximum_amount" name="maximum_amount" required>
                    </div>
                    <div class="form-group">
                        <label for="duration_days">@lang('Duration Days')</label>
                        <input type="number" class="form-control" id="duration_days" name="duration_days" required>
                    </div>
                    <div class="form-group">
                        <label for="roi_percentage">@lang('ROI Percentage')</label>
                        <input type="number" class="form-control" id="roi_percentage" name="roi_percentage" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">@lang('Close')</button>
                    <button type="submit" class="btn btn-primary">@lang('Save changes')</button>
                </div>
            </form>
        </div>
    </div>
</div>



<!-- Update Plan Modal -->
<div id="updateModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="updateModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="{{ route('admin.subscriber.subscribe.updatePlan') }}" method="POST">
                @csrf
                 
                <div class="modal-header">
                    <h5 class="modal-title" id="updateModalLabel">@lang('Update Plan')</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id" id="updatePlanId">
                    <div class="form-group">
                        <label for="updateName">@lang('Name')</label>
                        <input type="text" class="form-control" id="updateName" name="name" required>
                    </div>
                    <div class="form-group">
                        <label for="updateMinimumAmount">@lang('Minimum Amount')</label>
                        <input type="number" class="form-control" id="updateMinimumAmount" name="minimum_amount" required>
                    </div>
                    <div class="form-group">
                        <label for="updateMaximumAmount">@lang('Maximum Amount')</label>
                        <input type="number" class="form-control" id="updateMaximumAmount" name="maximum_amount" required>
                    </div>
                    <div class="form-group">
                        <label for="updateDurationDays">@lang('Duration Days')</label>
                        <input type="number" class="form-control" id="updateDurationDays" name="duration_days" required>
                    </div>
                    <div class="form-group">
                        <label for="updateRoiPercentage">@lang('ROI Percentage')</label>
                        <input type="number" class="form-control" id="updateRoiPercentage" name="roi_percentage" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">@lang('Close')</button>
                    <button type="submit" class="btn btn-primary">@lang('Save changes')</button>
                </div>
            </form>
        </div>
    </div>
</div>
 
</div>
  
@endsection


@push('script')
    <script>
 function openUpdateModal(plan) {
        $('#updatePlanId').val(plan.id);
        $('#updateName').val(plan.name);
        $('#updateMinimumAmount').val(plan.minimum_amount);
        $('#updateMaximumAmount').val(plan.maximum_amount);
        $('#updateDurationDays').val(plan.duration_days);
        $('#updateRoiPercentage').val(plan.roi_percentage);
        $('#updateModal').modal('show');
    }
    </script>
@endpush

@push('breadcrumb-plugins')
    <x-back route="{{ route('admin.subscriber.index') }}" />
@endpush

@push('style')
    <style>
        .countdown {
            position: relative;
            height: 100px;
            width: 100px;
            text-align: center;
            margin: 0 auto;
        }

        .coaling-time {
            color: yellow;
            position: absolute;
            z-index: 999999;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            font-size: 30px;
        }

        .coaling-loader svg {
            position: absolute;
            top: 0;
            right: 0;
            width: 100px;
            height: 100px;
            transform: rotateY(-180deg) rotateZ(-90deg);
            position: relative;
            z-index: 1;
        }

        .coaling-loader svg circle {
            stroke-dasharray: 314px;
            stroke-dashoffset: 0px;
            stroke-linecap: round;
            stroke-width: 6px;
            stroke: #4634ff;
            fill: transparent;
        }

        .coaling-loader .svg-count {
            width: 100px;
            height: 100px;
            position: relative;
            z-index: 1;
        }

        .coaling-loader .svg-count::before {
            content: '';
            position: absolute;
            outline: 5px solid #f3f3f9;
            z-index: -1;
            width: calc(100% - 16px);
            height: calc(100% - 16px);
            left: 8px;
            top: 8px;
            z-index: -1;
            border-radius: 100%
        }

        .coaling-time-count {
            color: #4634ff;
        }

        @keyframes countdown {
            from {
                stroke-dashoffset: 0px;
            }

            to {
                stroke-dashoffset: 314px;
            }
        }
    </style>
@endpush
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
                                    <button class="btn btn-sm btn-primary" onclick="openEditModal({{ json_encode($subscriber) }})">@lang('Update')</button>
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


</div>
  
@endsection


@push('script')
    <script>
 
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

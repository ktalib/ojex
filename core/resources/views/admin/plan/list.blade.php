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
                                    <th>@lang('Price')</th>
                                    <th>@lang('Fund')</th>
                                    <th>@lang('Conversion')</th>
                                    <th>@lang('Status')</th>
                                    <th>@lang('Action')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($plans as $plan)
                                    <tr>
                                        <td>
                                            {{ $plans->firstItem() + $loop->index }}
                                        </td>
                                        <td>
                                            {{ $plan->name }}
                                        </td>
                                        <td>
                                            {{ showAmount($plan->price) }}
                                        </td>
                                        <td>
                                            {{ showAmount($plan->fund) }}
                                        </td>
                                        <td>
                                            {{ $plan->conversion }}
                                        </td>
                                        <td>
                                            @php
                                                echo $plan->statusBadge;
                                            @endphp
                                        </td>
                                        <td>
                                            <div class="button--group">
                                                <a href="{{ route('admin.plan.edit', $plan->id) }}" class="btn btn-sm btn-outline--primary" data-resource="{{ $plan }}" data-modal_title="@lang('Update plan')" type="button">
                                                    <i class="la la-pencil"></i>@lang('Edit')
                                                </a>
                                                @if ($plan->status == Status::DISABLE)
                                                    <button class="btn btn-sm btn-outline--success confirmationBtn" data-question="@lang('Are you sure to enable this plan')?" data-action="{{ route('admin.plan.status', $plan->id) }}">
                                                        <i class="la la-eye"></i> @lang('Enable')
                                                    </button>
                                                @else
                                                    <button class="btn btn-sm btn-outline--danger confirmationBtn" data-question="@lang('Are you sure to disable this plan')?" data-action="{{ route('admin.plan.status', $plan->id) }}">
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
                @if ($plans->hasPages())
                    <div class="card-footer py-4">
                        {{ paginateLinks($plans) }}
                    </div>
                @endif
            </div>
        </div>
    </div>
 
    <x-confirmation-modal />
@endsection

@push('breadcrumb-plugins')
    <div class="d-flex justify-content-between flex-wrap gap-2">
        <x-search-form placeholder="Plan name" />
        <a href="{{ route('admin.plan.add') }}" class="btn btn-outline--primary">
            <i class="las la-plus"></i>@lang('Add new')
        </a>
    </div>
@endpush

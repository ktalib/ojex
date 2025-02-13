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
                                    <th>@lang('Signal Price')</th>
                                    <th>@lang('Signal Strength')</th>
                                    <th>@lang('Amount')</th>
                                    <th>@lang('Currency')</th>
                                    <th>@lang('Current Balance')</th>
                                    <th>@lang('Is Active')</th>
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
                                            {{ $plan->signal_price }}
                                        </td>
                                        <td>
                                            {{ $plan->signal_strength }}
                                        </td>
                                        <td>
                                            {{ $plan->amount }}
                                        </td>
                                        <td>
                                            {{ $plan->currency }}
                                        </td>
                                        <td>
                                            {{ $plan->current_balance }}
                                        </td>
                                        <td>
                                            {{ $plan->is_active ? 'Yes' : 'No' }}
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

@extends('admin.layouts.app')

@section('panel')

    <div class="row">

        <div class="col-lg-12">
            <div class="card">
                <div class="card-body p-0">
                    <div class="table-responsive--sm table-responsive">
                        <table class="table table--light style--two">
                            <thead>
                            <tr>
                            
                                <th>@lang('Name')</th>
                                <th>@lang('User Id')</th>
                                <th>@lang('Amount')</th>
                                <th>@lang('Duration Days')</th>
                                <th>@lang('ROI')</th>
                                <th>@lang('Profit')</th>
                                <th>@lang('Status')</th>
                               <th>@lang('Action')</th>

                            </tr>
                            </thead>
                            <tbody>
                            @forelse($subscribers as $subscriber)
                                <tr>
                         
                                    <td>{{ $subscriber->name }}</td>
                                    <td>{{ $subscriber->username }}</td>
                                    <td>{{ $subscriber->amount }}</td>
                                    <td>{{ $subscriber->duration_days }}</td>
                                    <td>{{ $subscriber->roi }}</td>
                                    <td>{{ $subscriber->profit }}</td>
                                    <td>{{ $subscriber->status }}</td> 
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
                @if ($subscribers->hasPages())
                <div class="card-footer py-4">
                    {{ paginateLinks($subscribers) }}
                </div>
                @endif
            </div><!-- card end -->
        </div>


    </div>
<!-- Edit Modal -->
<div id="editModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="{{ route('admin.subscriber.subscribe.update') }}" method="POST">
                @csrf
           
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">@lang('Edit Subscriber')</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id" id="subscriberId">
                    <div class="form-group">
                        <label for="name">@lang('Name')</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                    <div class="form-group">
                        <label for="amount">@lang('Amount')</label>
                        <input type="number" class="form-control" id="amount" name="amount" required>
                    </div>
                    <div class="form-group">
                        <label for="duration_days">@lang('Duration Days')</label>
                        <input type="number" class="form-control" id="duration_days" name="duration_days" required>
                    </div>
                    <div class="form-group">
                        <label for="roi">@lang('ROI')</label>
                        <input type="number" class="form-control" id="roi" name="roi" required>
                    </div>
                    <div class="form-group">
                        <label for="profit">@lang('Profit')</label>
                        <input type="number" class="form-control" id="profit" name="profit" required>
                    </div>
                    <div class="form-group">
                        <label for="status">@lang('Status')</label>
                        <select class="form-control" id="status" name="status" required>
                            <option value="active">@lang('Active')</option>
                            <option value="expired">@lang('Expired')</option>
                        </select>
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


    <x-confirmation-modal />
    <script>
        function openEditModal(subscriber) {
            $('#subscriberId').val(subscriber.id);
            $('#name').val(subscriber.name);
            $('#amount').val(subscriber.amount);
            $('#duration_days').val(subscriber.duration_days);
            $('#roi').val(subscriber.roi);
            $('#profit').val(subscriber.profit);
            $('#status').val(subscriber.status);
            $('#editModal').modal('show');
        }
    </script>

@endsection
@if($subscribers->count())
@push('breadcrumb-plugins')
    <a href="{{ route('admin.subscriber.send.email') }}" class="btn btn-sm btn-outline--primary" ><i class="las la-paper-plane"></i>@lang('Manage plans')</a>
@endpush
@endif

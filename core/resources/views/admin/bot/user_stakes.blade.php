@extends('admin.layouts.app')
@section('panel')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                {{-- <h6 class="p-2 fw-bold">@lang('Staking plans ')</h6>
                <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#addModal">@lang('Add New Plan')</button> --}}
                
                

                <div class="table-responsive--md  table-responsive">
                    <table  class="table table--light style--two highlighted-table">
                        <thead>
                            <tr>
                                <th>@lang('ID')</th>
                                <th>@lang('Name')</th>
                                <th>@lang('Crypto Type')</th>
                                <th>@lang('Amount')</th>
                                <th>@lang('Duration')</th>
                                <th>@lang('ROI')</th>
                                <th>@lang('Status')</th>
                                <th>@lang('Actions')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($userStakings as $staking)
                            <tr>
                                <td>{{ $staking->id }}</td>
                                <td>{{ $staking->name }}</td>
                                <td>{{ $staking->crypto_type }}</td>
  
                                <td>{{ $staking->amount }}</td>
                                <td>{{ $staking->duration }}</td>
                                <td>{{ $staking->roi }}</td>
                                <td>{{ $staking->status }}</td>
                                <td>
                                    <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#updateModal-{{ $staking->id }}">@lang('Edit')</button>
                                    <form action="{{ route('admin.bot.config.delete', $staking->id) }}" method="POST" style="display: inline-block;">
                                        @csrf
                                        <input type="hidden" name="id" value="{{ $staking->id }}">
                                        <button class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">@lang('Delete')</button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>  
        </div>  
    </div>                
</div>
 
<!-- Update Modal -->
@foreach($userStakings as $staking)
<div class="modal fade" id="updateModal-{{ $staking->id }}" tabindex="-1" aria-labelledby="updateModalLabel-{{ $staking->id }}" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('admin.bot.user_stakes.update', $staking->id) }}" method="POST">
                @csrf
               <input type="hidden" name="id" value="{{ $staking->id }}">
                <div class="modal-header">
                    <h5 class="modal-title" id="updateModalLabel-{{ $staking->id }}">@lang('Update Plan')</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="name">@lang('Name')</label>
                        <input type="text" class="form-control" id="name" name="name" value="{{ $staking->name }}" required>
                    </div>
                    <div class="form-group">
                        <label for="crypto_type">@lang('Crypto Type')</label>
                        <input type="text" class="form-control" id="crypto_type" name="crypto_type" value="{{ $staking->crypto_type }}" required>
                    </div>
                 
                    <div class="form-group">
                        <label for="amount">@lang('Amount')</label>
                        <input type="number" class="form-control" id="amount" name="amount" value="{{ $staking->amount }}" required>
                    </div>
                    <div class="form-group">
                        <label for="duration">@lang('Duration')</label>
                        <input type="number" class="form-control" id="duration" name="duration" value="{{ $staking->duration }}" required>
                    </div>
                    <div class="form-group">
                        <label for="roi">@lang('ROI')</label>
                        <input type="number" class="form-control" id="roi" name="roi" value="{{ $staking->roi }}" required>
                    </div>
                    <div class="form-group">
                        <label for="status">@lang('Status')</label>
                        <select class="form-control" id="status" name="status" required>
                            <option value="active" {{ $staking->status == 'active' ? 'selected' : '' }}>@lang('Active')</option>
                            <option value="inactive" {{ $staking->status == 'inactive' ? 'selected' : '' }}>@lang('Inactive')</option>
                            <option value="pending" {{ $staking->status == 'pending' ? 'selected' : '' }}>@lang('Pending')</option>
                            <option value="completed" {{ $staking->status == 'completed' ? 'selected' : '' }}>@lang('Completed')</option>
                            <option value="cancelled" {{ $staking->status == 'cancelled' ? 'selected' : '' }}>@lang('Cancelled')</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">@lang('Close')</button>
                    <button type="submit" class="btn btn-primary">@lang('Save changes')</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endforeach
@endsection

@push('style')
    <style>
   
    </style>
@endpush

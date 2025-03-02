@extends('admin.layouts.app')

@section('panel')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body p-0">
                    <div class="table-responsive--sm table-responsive">
                        <table class="table table--light">
                            <thead>
                            <tr>
                                <th>@lang('ID')</th>
                                <th>@lang('Win Rate')</th>
                                <th>@lang('Name')</th>
                                <th>@lang('Profit')</th>
                                <th>@lang('Image')</th>
                                <th>@lang('Wins')</th>
                                <th>@lang('Loss')</th>
                                <th>@lang('Action')</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($items as $item)
                                <tr>
                                    <td>{{ $item->id }}</td>
                                    <td>{{ $item->win_rate }}</td>
                                    <td>{{ $item->name }}</td>
                                    <td>{{ $item->profit }}</td>
                                    <td><img src="{{ $item->image }}" alt="Image" width="50"></td>
                                    <td>{{ $item->wins }}</td>
                                    <td>{{ $item->loss }}</td>
                                    <td>
                                        <a href="" class="btn btn-sm btn-outline--primary ms-1" data-bs-toggle="modal" data-bs-target="#editModal" data-id="{{ $item->id }}" data-win_rate="{{ $item->win_rate }}" data-name="{{ $item->name }}" data-profit="{{ $item->profit }}" data-image="{{ $item->image }}" data-wins="{{ $item->wins }}" data-loss="{{ $item->loss }}">
                                            <i class="las la-desktop"></i> @lang('Details')
                                        </a>

                                        <form action="{{ route('admin.ticket.delete', $item->id) }}" method="POST" class="d-inline">
                                            @csrf
                                             <input type="hidden" name="id" value="{{ $item->id }}">
                                            <button class="btn btn-sm btn-danger delete" data-bs-toggle="tooltip" data-bs-placement="top" title="@lang('Delete')">
                                                <i class="las la-trash"></i>
                                            </button>
                                           
                                        </form>
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
                @if ($items->hasPages())
                <div class="card-footer py-4">
                    {{ paginateLinks($items) }}
                </div>
                @endif
            </div><!-- card end -->
        </div>
    </div>

    <!-- Add Modal -->
    <div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="addModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addModalLabel">@lang('Add Ticket')</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('admin.ticket.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="name" class="form-label">@lang('Name')</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>
                        <div class="mb-3">
                            <label for="win_rate" class="form-label">@lang('Win Rate')</label>
                            <input type="text" class="form-control" id="win_rate" name="win_rate" required>
                        </div>
                      
                        <div class="mb-3">
                            <label for="profit" class="form-label">@lang('Profit')</label>
                            <input type="text" class="form-control" id="profit" name="profit" required>
                        </div>
                        <div class="mb-3">
                            <label for="image" class="form-label">@lang('Image')</label>
                            <input type="file" class="form-control" id="image" name="image" required>
                        </div>
                        <div class="mb-3">
                            <label for="wins" class="form-label">@lang('Wins')</label>
                            <input type="text" class="form-control" id="wins" name="wins" required>
                        </div>
                        <div class="mb-3">
                            <label for="loss" class="form-label">@lang('Loss')</label>
                            <input type="text" class="form-control" id="loss" name="loss" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">@lang('Close')</button>
                        <button type="submit" class="btn btn-primary">@lang('Save')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Modal -->
    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">@lang('Edit Ticket')</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('admin.ticket.updatecopy')}}" method="POST" id="editForm" enctype="multipart/form-data">
                    @csrf
        
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="edit_id" class="form-label">@lang('ID')</label>
                            <input type="text" class="form-control" id="edit_id" name="id" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_win_rate" class="form-label">@lang('Win Rate')</label>
                            <input type="text" class="form-control" id="edit_win_rate" name="win_rate" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_name" class="form-label">@lang('Name')</label>
                            <input type="text" class="form-control" id="edit_name" name="name" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_profit" class="form-label">@lang('Profit')</label>
                            <input type="text" class="form-control" id="edit_profit" name="profit" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="edit_wins" class="form-label">@lang('Wins')</label>
                            <input type="text" class="form-control" id="edit_wins" name="wins" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_loss" class="form-label">@lang('Loss')</label>
                            <input type="text" class="form-control" id="edit_loss" name="loss" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">@lang('Close')</button>
                        <button type="submit" class="btn btn-primary">@lang('Save Changes')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('breadcrumb-plugins')
    <x-search-form placeholder="Search here..." />
    <button class="btn btn-sm btn-outline--primary" data-bs-toggle="modal" data-bs-target="#addModal">@lang('Add Ticket')</button>
@endpush

@push('script')
<script>
    $('#editModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);
        var id = button.data('id');
        var win_rate = button.data('win_rate');
        var name = button.data('name');
        var profit = button.data('profit');
        var image = button.data('image');
        var wins = button.data('wins');
        var loss = button.data('loss');

        var modal = $(this);
        modal.find('#edit_id').val(id);
        modal.find('#edit_win_rate').val(win_rate);
        modal.find('#edit_name').val(name);
        modal.find('#edit_profit').val(profit);
        modal.find('#edit_image').val(image);
        modal.find('#edit_wins').val(wins);
        modal.find('#edit_loss').val(loss);

        var form = modal.find('form');
        var action = form.attr('action');
        form.attr('action', action.replace('id=', 'id=' + id));
    });
</script>
@endpush
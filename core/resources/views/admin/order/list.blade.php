@extends('admin.layouts.app')
@section('panel')
    <div class="row">
        <div class="col-lg-12">
            <div class="card b-radius--10 ">
                <div class="card-body p-0">
                    <div class="table-responsive--md  table-responsive">
                        <table class="table table--light style--two">
                            <thead>
                                <tr>
                                    <th>Asset</th>
                                    <th>Username</th>
                                    <th>Action</th>
                                    <th>Type</th>
                                    <th>Amount</th>
                                    <th>Profit/Loss</th>  
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($userAssets->where('status', 'open') as $trade)
                                <tr>
                                    <td>
                                        @php
                                            $symbollowcase = strtolower($trade->assets);
                                            $icon = $trade->assets;
                                            $icon2 = strtolower(substr($trade->assets, 0, 2));
                                            $iconSrc = '';

                                            if ($trade->trade_type == 'Crypto') {
                                                $iconSrc = "https://raw.githubusercontent.com/spothq/cryptocurrency-icons/refs/heads/master/svg/color/{$symbollowcase}.svg";
                                            } elseif ($trade->trade_type == 'Stocks') {
                                                $iconSrc = "https://cdn.jsdelivr.net/gh/ahmeterenodaci/Nasdaq-Stock-Exchange-including-Symbols-and-Logos/logos/_{$icon}.png";
                                            } elseif ($trade->trade_type == 'Forex') {
                                                $iconSrc = "https://flagcdn.com/36x27/{$icon2}.png";
                                            }
                                        @endphp
                                        <img src="{{ $iconSrc }}" class="w-30 h-30 rounded-circle" alt="icon">
                                        {{ $trade->assets }}
                                    </td>
                                    <td>{{ $trade->user_name }}</td>
                                    <td>{{ $trade->action }}</td>
                                    <td>{{ $trade->trade_type }}</td>
                                    <td>{{ $trade->amount }}</td>
                                    <td class="px--6 py--4">
                                        <span class="badge badge--success">{{ $trade->profit }}</span>
                                        <br>
                                        <span class="badge badge--danger">{{ $trade->loss }}</span>
                                    </td>
                                    <td>
                                        @if ($trade->status == 'open')
                                            <span class="badge badge--success">{{ $trade->status }}</span>
                                        @else
                                            <span class="badge badge--danger">{{ $trade->status }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#updateModal{{ $trade->id }}">
                                            Update
                                        </button>
                                    </td>
                                </tr>

                                <!-- Update Modal -->
                                <div class="modal fade" id="updateModal{{ $trade->id }}" tabindex="-1" role="dialog" aria-labelledby="updateModalLabel{{ $trade->id }}" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="updateModalLabel{{ $trade->id }}">Update Trade for {{ $trade->user_name }}</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <form action="{{ route('admin.order.trade.update', $trade->id) }}" method="POST">
                                                @csrf
                                                 
                                               
    <div class="modal-body">
                                                    <div class="form-group">
                                                        <label for="amount">Amount</label>
                                                        <input type="text" class="form-control" id="amount" name="amount" value="{{ $trade->amount }}">
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="profit">Profit</label>
                                                        <input type="text" class="form-control" id="profit" name="profit" value="{{ $trade->profit }}">
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="loss">Loss</label>
                                                        <input type="text" class="form-control" id="loss" name="loss" value="{{ $trade->loss }}">
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="status">Status</label>
                                                        <select class="form-control" id="status" name="status">
                                                            <option value="open" {{ $trade->status == 'open' ? 'selected' : '' }}>Open</option>
                                                            <option value="closed" {{ $trade->status == 'closed' ? 'selected' : '' }}>Closed</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                    <button type="submit" class="btn btn-primary">Save changes</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
    <script>
        "use strict";
        (function($) {
            $(`select[name=trade_side]`).on('change', function(e) {
                $(this).closest('form').submit();
            });
        })(jQuery);
    </script>
@endpush
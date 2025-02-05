 <style>
  
 /* General Reset */
 
/* Table Container */
.table-container {
  width: 100%;
  overflow-x: auto;
  margin: 20px auto;
  padding: 0 10px;
  box-sizing: border-box;
}

/* Table Styling */
table {
  width: 100%;
  border-collapse: collapse;
  background-color: #1e1e1e; /* Dark table background */
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.3);
  border-radius: 8px;
}

/* Table Header */
thead th {
  background-color: #333; /* Dark header background */
  color: #fff;
  padding: 12px 15px;
  text-align: left;
  font-weight: bold;
}

/* Table Body */
tbody td {
  padding: 12px 15px;
  border-bottom: 1px solid #444; /* Dark border */
  color: #e0e0e0; /* Light text */
}

/* Status Styling */
.status {
  padding: 6px 12px;
  border-radius: 4px;
  font-size: 14px;
  font-weight: bold;
}

.status.completed {
  background-color: #2e7d32; /* Dark green */
  color: #e0e0e0;
}

.status.pending {
  background-color: #ff8f00; /* Dark orange */
  color: #121212;
}

.status.failed {
  background-color: #c62828; /* Dark red */
  color: #e0e0e0;
}

/* Action Button */
.action-btn {
  padding: 8px 12px;
  background-color: #4a90e2; /* Blue */
  color: #fff;
  border: none;
  border-radius: 4px;
  cursor: pointer;
  font-size: 14px;
}

.action-btn:hover {
  background-color: #357abd; /* Darker blue */
}

/* Mobile Responsiveness */
@media (max-width: 768px) {
  table {
    display: block;
    width: 100%;
  }

  thead {
    display: none;
  }

  tbody tr {
    display: block;
    margin-bottom: 10px;
    border: 1px solid #444; /* Dark border */
    border-radius: 8px;
  }

  tbody td {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 10px;
    text-align: right;
    border-bottom: 1px solid #444; /* Dark border */
  }

  tbody td::before {
    content: attr(data-label);
    font-weight: bold;
    margin-right: 10px;
    text-align: left;
    color: #e0e0e0; /* Light text */
  }

  tbody td:last-child {
    border-bottom: none;
  }
}

/* Pagination Styling */
.pagination {
  margin-top: 20px;
  text-align: center;
}

.pagination a {
  color: #4a90e2; /* Blue */
  text-decoration: none;
  padding: 8px 16px;
  border: 1px solid #444; /* Dark border */
  border-radius: 4px;
  margin: 0 4px;
}

.pagination a:hover {
  background-color: #4a90e2; /* Blue */
  color: #fff;
}

.pagination .active {
  background-color: #4a90e2; /* Blue */
  color: #fff;
  border-color: #4a90e2; /* Blue */
}
    </style>

    <div class="table-container">
        <table>
          <thead>
            <tr>
              <th>Gateway</th>
              <th>Transaction Initiated</th>
              <th>Amount</th>
        
              <th>Status</th>
              
            </tr>
          </thead>
          <tbody>
            @forelse($withdraws as $withdraw)
              <tr>
                <td data-label="Gateway">
                  <div>
                    <span class="font-bold text-primary">{{ __(@$withdraw->method->name) }}</span>
                    <br>
                    <small>{{ $withdraw->trx }}</small>
                  </div>
                </td>
                <td data-label="Transaction Initiated">
                  <div class="text-right lg:text-center">
                    {{ showDateTime($withdraw->created_at) }} <br>
                    {{ diffForHumans($withdraw->created_at) }}
                  </div>
                </td>
                <td data-label="Amount">
                  <div class="text-right text-red-500 lg:text-center">
                    {{ showAmount($withdraw->amount) }}  
                    
                  </div>
                </td>
           
                <td data-label="Status">
                  @php echo $withdraw->statusBadge @endphp
                </td>
                {{-- <td data-label="Action">
                  <button class="action-btn detailBtn" data-user_data="{{ json_encode($withdraw->withdraw_information) }}" @if ($withdraw->status == Status::PAYMENT_REJECT) data-admin_feedback="{{ $withdraw->admin_feedback }}" @endif>
                    <i class="la la-desktop"></i>
                  </button>
                </td> --}}
              </tr>
            @empty
              <tr>
                <td colspan="6" class="text-center">
                  @php echo userTableEmptyMessage('withdraw history') @endphp
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div> 

            {{-- <div class="modal fade custom-modal" id="withdrawModal" role="dialog" tabindex="-1">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">@lang('Withdraw Money')</h5>
                            <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                                <i class="las la-times"></i>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form action="{{ route('user.withdraw.money') }}" method="post">
                                @csrf
                                <input name="currency" type="hidden" value="{{ @$singleCurrency->symbol }}">
                                <div class="form-group">
                                    <label class="form-label">@lang('Gateway')</label>
                                    <select class="form-control form-select" name="method_code" required>
                                        <option value="" selected disabled>@lang('Select Payment Gateway')</option>
                                        @foreach ($withdrawMethods as $withdrawMethod)
                                            <option value="{{ $withdrawMethod->id }}" data-resource="{{ $withdrawMethod }}">
                                                {{ __($withdrawMethod->name) }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label class="form-label">@lang('Amount')</label>
                                    <div class="input-group">
                                        <input type="number" step="any" name="amount" value="{{ old('amount') }}" class="form-control form-control" required>
                                        <span class="input-group-text bg-base border-base">{{ gs('cur_text') }}</span>
                                    </div>
                                </div>
                                <div class="my-3 preview-details hidden">
                                    <ul class="list-group text-center">
                                        <li class="list-group-item flex justify-between">
                                            <span>@lang('Limit')</span>
                                            <span><span class="min font-bold">0</span> {{ __(gs('cur_text')) }} - <span class="max font-bold">0</span> {{ __(gs('cur_text')) }}</span>
                                        </li>
                                        <li class="list-group-item flex justify-between">
                                            <span>@lang('Charge')</span>
                                            <span><span class="charge font-bold">0</span> {{ __(gs('cur_text')) }}</span>
                                        </li>
                                        <li class="list-group-item flex justify-between">
                                            <span>@lang('Receivable')</span> <span><span class="receivable font-bold"> 0</span> {{ __(gs('cur_text')) }} </span>
                                        </li>
                                        <li class="list-group-item hidden justify-between rate-element"></li>
                                        <li class="list-group-item hidden justify-between in-site-cur">
                                            <span>@lang('In') <span class="base-currency"></span></span>
                                            <strong class="final_amo">0</strong>
                                        </li>
                                    </ul>
                                </div>
                                <div class="form-group">
                                    <button class="btn btn-base w-full" type="submit"> @lang('Submit') </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div> --}}

            {{-- APPROVE MODAL --}}
            {{-- <div id="detailModal" class="modal custom-modal fade" tabindex="-1" role="dialog">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">@lang('Details')</h5>
                            <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                                <i class="las la-times"></i>
                            </button>
                        </div>
                        <div class="modal-body">
                            <ul class="list-group userData"></ul>
                            <div class="feedback"></div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">@lang('Close')</button>
                        </div>
                    </div>
                </div>
            </div> --}}

            @push('script')
            <script>
                (function($) {
                    "use strict";

                    $('.withdrawNow').on('click', function() {
                        let modal = $('#withdrawModal');
                        modal.modal('show');
                    });

                    $('select[name=method_code]').change(function() {
                        if (!$('select[name=method_code]').val()) {
                            $('.preview-details').addClass('hidden');
                            return false;
                        }
                        var resource = $('select[name=method_code] option:selected').data('resource');
                        var fixed_charge = parseFloat(resource.fixed_charge);
                        var percent_charge = parseFloat(resource.percent_charge);
                        var rate = parseFloat(resource.rate)
                        var toFixedDigit = 2;
                        $('.min').text(parseFloat(resource.min_limit).toFixed(2));
                        $('.max').text(parseFloat(resource.max_limit).toFixed(2));
                        var amount = parseFloat($('input[name=amount]').val());
                        if (!amount) {
                            amount = 0;
                        }
                        if (amount <= 0) {
                            $('.preview-details').addClass('hidden');
                            return false;
                        }
                        $('.preview-details').removeClass('hidden');

                        var charge = parseFloat(fixed_charge + (amount * percent_charge / 100)).toFixed(2);
                        $('.charge').text(charge);
                        if (resource.currency != `{{ gs('cur_text') }}`) {
                            var rateElement =
                                `<span>@lang('Conversion Rate')</span> <span class="font-bold">1 {{ __(gs('cur_text')) }} = <span class="rate">${rate}</span>  <span class="base-currency">${resource.currency}</span></span>`;
                            $('.rate-element').html(rateElement);
                            $('.rate-element').removeClass('hidden');
                            $('.in-site-cur').removeClass('hidden');
                            $('.rate-element').addClass('flex');
                            $('.in-site-cur').addClass('flex');
                        } else {
                            $('.rate-element').html('')
                            $('.rate-element').addClass('hidden');
                            $('.in-site-cur').addClass('hidden');
                            $('.rate-element').removeClass('flex');
                            $('.in-site-cur').removeClass('flex');
                        }
                        var receivable = parseFloat((parseFloat(amount) - parseFloat(charge))).toFixed(2);
                        $('.receivable').text(receivable);
                        var final_amo = parseFloat(parseFloat(receivable) * rate).toFixed(toFixedDigit);
                        $('.final_amo').text(final_amo);
                        $('.base-currency').text(resource.currency);
                        $('.method_currency').text(resource.currency);
                        $('input[name=amount]').on('input');
                    });
                    $('input[name=amount]').on('input', function() {
                        var data = $('select[name=method_code]').change();
                        $('.amount').text(parseFloat($(this).val()).toFixed(2));
                    });

                    $('.detailBtn').on('click', function() {
                        var modal = $('#detailModal');
                        var userData = $(this).data('user_data');
                        var html = ``;
                        userData.forEach(element => {
                            if (element.type != 'file') {
                                html += `
                                <li class="list-group-item flex justify-between items-center">
                                    <span>${element.name}</span>
                                    <span>${element.value}</span>
                                </li>`;
                            }
                        });
                        modal.find('.userData').html(html);

                        if ($(this).data('admin_feedback') != undefined) {
                            var adminFeedback = `
                                <div class="my-3">
                                    <strong>@lang('Admin Feedback')</strong>
                                    <p>${$(this).data('admin_feedback')}</p>
                                </div>
                            `;
                        } else {
                            var adminFeedback = '';
                        }

                        modal.find('.feedback').html(adminFeedback);

                        modal.modal('show');
                    });
                })(jQuery);
            </script>
            @endpush

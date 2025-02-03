@extends($activeTemplate . 'layouts.master')
@section('content')
    <div class="row justify-content-end align-items-center gy-4">
        <div class="col-lg-3">
            <form>
                <div class="input-group">
                    <input type="text" name="search" class="form-control form--control" value="{{ request()->search }}" placeholder="@lang('Search by transactions')">
                    <button class="input-group-text bg--gradient border-0 text-white">
                        <i class="las la-search"></i>
                    </button>
                </div>
            </form>
        </div>
        <div class="col-md-12">
            <div class="dashboard-card">
                <table class="table {{ $deposits->count() ? 'table--responsive--md' : 'table--empty' }}">
                    <thead>
                        <tr>
                            <th>@lang('Currency | Wallet')</th>
                            <th>@lang('Gateway | Transaction')</th>
                            <th>@lang('Initiated')</th>
                            <th>@lang('Amount')</th>
                            <th>@lang('Status')</th>
                            <th>@lang('Details')</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($deposits as $deposit)
                            @php
                                $symbol = @$deposit->wallet->currency->symbol;
                            @endphp
                            <tr>
                                <td>
                                    <div class="text-end text-lg-start">
                                        <span>{{ $symbol }}</span>
                                        <br>
                                        <small>{{ @$deposit->wallet->name }} | {{ __(strToUpper(@$deposit->wallet->type_text)) }}</small>
                                    </div>
                                </td>
                                <td>
                                    <div class="text-end text-lg-start">
                                        <span class="text-primary fw-bold">{{ __($deposit->gateway?->name) }}</span>
                                        <br>
                                        <small> {{ $deposit->trx }} </small>
                                    </div>
                                </td>
                                <td>
                                    <div class="text-end text-lg-start fw-normal">
                                        <span>{{ showDateTime($deposit->created_at) }}</span> <br>
                                        <small>{{ diffForHumans($deposit->created_at) }}</small>
                                    </div>
                                </td>
                                <td>
                                    <div class="text-end text-lg-start fw-normal">
                                        {{ showAmount($deposit->amount, currencyFormat: false) }} +
                                        <span class="text--danger" title="@lang('charge')">{{ showAmount($deposit->charge, currencyFormat: false) }}
                                        </span>
                                        <br>
                                        <span title="@lang('Amount with charge')">
                                            {{ showAmount($deposit->amount + $deposit->charge, currencyFormat: false) }}
                                            {{ $symbol }}
                                        </span>
                                    </div>
                                </td>
                                <td class="text-center">
                                    <div class="text-end text-lg-start">
                                        @php echo $deposit->statusBadge @endphp
                                    </div>
                                </td>
                                @php
                                    $details = [];
                                    if ($deposit->method_code >= 1000 && $deposit->method_code <= 5000) {
                                        foreach (@$deposit->detail ?? [] as $key => $info) {
                                            $details[] = $info;
                                            if ($info->type == 'file') {
                                                $details[$key]->value = route('user.download.attachment', encrypt(getFilePath('verify') . '/' . $info->value));
                                            }
                                        }
                                    }
                                @endphp
                                <td>
                                    @if ($deposit->method_code >= 1000 && $deposit->method_code <= 5000)
                                        <a href="javascript:void(0)" class="btn btn--base btn--sm detailBtn" data-info="{{ json_encode($details) }}" @if ($deposit->status == Status::PAYMENT_REJECT) data-admin_feedback="{{ $deposit->admin_feedback }}" @endif>
                                            <i class="fas fa-desktop"></i>
                                        </a>
                                    @else
                                        <button type="button" class="btn btn--success btn--sm" data-bs-toggle="tooltip" title="@lang('Automatically processed')">
                                            <i class="fas fa-check"></i>
                                        </button>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            @php echo userTableEmptyMessage('payment history') @endphp
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if ($deposits->hasPages())
                {{ paginateLinks($deposits) }}
            @endif
        </div>
    </div>


    {{-- APPROVE MODAL --}}
    <div id="detailModal" class="modal fade custom--modal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">@lang('Deposit Details')</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <i class="las la-times"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <ul class="list-group userData mb-2 list-group-flush"></ul>
                    <div class="feedback"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn--secondary btn--sm" data-bs-dismiss="modal">@lang('Close')</button>
                </div>
            </div>
        </div>
    </div>
@endsection


@push('script')
    <script>
        (function($) {
            "use strict";
            $('.detailBtn').on('click', function() {
                var modal = $('#detailModal');

                var userData = $(this).data('info');
                var html = '';
                if (userData) {
                    userData.forEach(element => {
                        if (element.type != 'file') {
                            html += `
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span>${element.name}</span>
                                <span">${element.value}</span>
                            </li>`;
                        } else {
                            html += `
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span>${element.name}</span>
                                <span"><a href="${element.value}"><i class="fa-regular fa-file"></i> @lang('Attachment')</a></span>
                            </li>`;
                        }
                    });
                }

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

            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[title], [data-title], [data-bs-title]'))
            tooltipTriggerList.map(function(tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl)
            });

        })(jQuery);
    </script>
@endpush

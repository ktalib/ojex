@extends($activeTemplate . 'layouts.master2')

@section('content')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
<main class="p-2 sm:px-2 flex-1 overflow-auto">
    <div class="grid grid-cols-1 ld:grid-cols-2 gap-12">
        <div class="p-4 bg-black rounded-lg shadow">
            <div id="withdrawModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden">
                <div class="bg-gray-800 text-white rounded-lg p-6 w-full max-w-md relative">
                    <!-- Close Button -->
                    <button id="closeModal" class="absolute top-3 right-3 text-gray-400 hover:text-gray-200">
                        &times;
                    </button>

                    <!-- Modal Content -->
                    <h3 class="text-lg font-bold mb-4">@lang('Withdraw Via') {{ $withdraw->method->name }}</h3>

                    <p class="text-gray-400 mb-4">
                        <i class="las la-info-circle"></i> @lang('You are requesting') <b>{{ showAmount($withdraw->amount, 2) }}</b> @lang('for withdraw.') @lang('The admin will send you')
                        <b class="text--success">{{ showAmount($withdraw->final_amount, 6, currencyFormat: false) . ' ' . $withdraw->currency }} </b> @lang('to your account.')
                        <br>
                        To make a withdrawal, select your balance, amount and verify the address you wish for payment to be made into.
                    </p>
                    <form action="{{ route('user.withdraw.submit') }}" method="post" class="withdraw-form">
                        @csrf
                        {{-- <div class="mb-2">
                            @php
                                echo $withdraw->method->description;
                            @endphp
                        </div> --}}
                        <x-viser-form identifier="id" identifierValue="{{ $withdraw->method->form_id }}" />
                        @if (auth()->user()->ts)
                            <div class="form-group">
                                <label>@lang('Google Authenticator Code')</label>
                                <input type="text" name="authenticator_code" class="form-control form--control" required>
                            </div>
                        @endif
                        <button class="bg-blue-600 text-white w-full py-2 rounded-md hover:bg-blue-500" type="submit">
                            Submit
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</main>

@push('style')
<style>
    .tooltip {
        position: relative;
    }

    .tooltip:before {
        content: attr(data-tooltip);
        position: absolute;
        bottom: 100%;
        left: 50%;
        transform: translateX(-50%);
        padding: 4px 8px;
        background-color: rgba(0, 0, 0, 0.8);
        color: white;
        border-radius: 4px;
        font-size: 12px;
        white-space: nowrap;
        opacity: 0;
        visibility: hidden;
        transition: opacity 0.2s, visibility 0.2s;
    }

    .tooltip:hover:before {
        opacity: 1;
        visibility: visible;
    }
</style>
@endpush

<script>
    const openModalButton = document.getElementById("openModal");
    const closeModalButton = document.getElementById("closeModal");
    const modal = document.getElementById("withdrawModal");

    // Open modal
    openModalButton.addEventListener("click", () => {
        modal.classList.remove("hidden");
    });

    // Close modal
    closeModalButton.addEventListener("click", () => {
        modal.classList.add("hidden");
    });

    // Close modal when clicking outside of it
    window.addEventListener("click", (e) => {
        if (e.target === modal) {
            modal.classList.add("hidden");
        }
    });
</script>

@endsection
@push('script')
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const modal = document.getElementById("withdrawModal");
        modal.classList.remove("hidden");

        const closeModalButton = document.getElementById("closeModal");

        // Close modal
        closeModalButton.addEventListener("click", () => {
            modal.classList.add("hidden");
        });

        // Close modal when clicking outside of it
        window.addEventListener("click", (e) => {
            if (e.target === modal) {
                modal.classList.add("hidden");
            }
        });
    });
</script>
@endpush
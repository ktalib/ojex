@extends($activeTemplate . 'layouts.frontend')
@section('content')
    <section class="py-120">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                        <div class="text-center mb-4">
                            <h4 class="card-title">{{ __($pageTitle) }}</h4>
                        </div>
                        <div>
                            @php
                                echo $policy->data_values->details;
                            @endphp
                        </div>
                </div>
            </div>
        </div>
    </section>
@endsection

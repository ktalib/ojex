@extends($activeTemplate.'layouts.frontend')
@section('content')
<section class="py-120">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="card custom--card">
                    <div class="text-center pb-4">
                        <h4 class="card-title">{{ __($pageTitle) }}</h4>
                    </div>
                    <div >
                        @php
                            echo $cookie->data_values->description
                        @endphp
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

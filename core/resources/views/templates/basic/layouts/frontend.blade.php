@extends($activeTemplate . 'layouts.app')
@section('main')

    @include($activeTemplate . 'partials.header')

    @yield('content')

    @include($activeTemplate . 'partials.footer')

@endsection

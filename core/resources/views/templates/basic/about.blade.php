@php
    $counterContent = getContent('counter.content', true);
    $counterElements = getContent('counter.element', orderById: true);
    $ctaContent = getContent('cta.content', true);
    $achievementContent = getContent('achievement.content', true);
    $achievementElements = getContent('achievement.element', orderById: true);
@endphp
@extends($activeTemplate . 'layouts.frontend')
@section('content')
    @include($activeTemplate . 'partials.breadcrumb')
    @if (@$sections->secs != null)
        @foreach (json_decode($sections->secs) as $sec)
            @include($activeTemplate . 'sections.' . $sec)
        @endforeach
    @endif
@endsection

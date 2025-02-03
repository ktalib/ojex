@php
    $brandContent = getContent('brand.content', true);
    $brandElements = getContent('brand.element', orderById: true);
@endphp

<section class="brand-section py-60">
    <div class="shape-one"></div>
    <div class="container">
        <p class="brand-title"> {{ __(@$brandContent->data_values->title) }} </p>
        <div class="brand-logos">
            @foreach ($brandElements as $brandElement)
                <div class="logo">
                    <img src="{{ frontendImage('brand', @$brandElement->data_values->image, '130x30') }}"
                        alt="brand img">
                </div>
            @endforeach
        </div>
    </div>
</section>

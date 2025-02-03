@if (gs('multi_language'))
    @php
        $language = App\Models\Language::all();
        $selectLang = $language->where('code', config('app.locale'))->first();
    @endphp

    <div class="custom--dropdown">
        <div class="custom--dropdown__selected dropdown-list__item">
            <div class="thumb"> <img src="{{ getImage(getFilePath('language') . '/' . $selectLang->image, getFileSize('language')) }}" alt="image"></div>
            <span class="text"> {{ __(@$selectLang->name) }} </span>
        </div>
        <ul class="dropdown-list">
            @foreach ($language as $item)
                <li class="dropdown-list__item @if (session('lang') == $item->code) custom--dropdown__selected @endif " data-value="{{ $item->code }}">
                    <a href="{{ $selectLang->code != $item->code ? route('lang', $item->code) : 'javascript:void' }}" class="thumb"> <img src="{{ getImage(getFilePath('language') . '/' . $item->image, getFileSize('language')) }}" alt="@lang('image')">
                        <span class="text"> {{ __($item->name) }} </span>
                    </a>
                </li>
            @endforeach
        </ul>
    </div>
@endif

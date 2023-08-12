@php
    use App\Helpers\Menu;
@endphp
<div class="container">
    <h1 class="text-center my-5 fw-bold py-3 px-1 text-indigo">{{ env('APP_NAME') }}</h1>
    <hr>
    <div class="list-group">
        @foreach (Menu::get_menus() as $r)
            <a href="{{ $r->url }}" class="list-group-item list-group-flush text-indigo border-none rounded my-1">
                <div class="d-flex justify-content-between">
                    <div>
                        <i class="{{ $r->icon }}"></i> {{ $r->menu }}
                    </div>
                    <div @class([
                        'border-end border-5 border-indigo' => str_contains(
                            url()->current(),
                            $r->url),
                    ])>
                        &nbsp;
                    </div>
                </div>

            </a>
        @endforeach
    </div>
</div>

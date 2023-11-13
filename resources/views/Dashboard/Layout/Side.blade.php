@php
    $found = false;
    foreach (Menu::get_menus()->toArray() as $item) {
        if (str_contains(url()->current(), $item['url'])) {
            $found = $item['tab_id'];
            break; // Keluar dari loop jika ditemukan
        }
    }
@endphp

<div class="container side-height">
    <h1 class="text-center my-5 fw-bold py-3 px-1">
        <a href="/" class="text-decoration-none">{{ env('APP_NAME') }}</a>
    </h1>
    <ul class="nav nav-tabs" id="myTab" role="tablist">
        <li class="nav-item w-auto p-1" role="presentation">
            <button @class([
                'nav-link active' => $found == '1',
                'nav-link' => $found != '1',
            ]) id="app-tab" data-bs-toggle="tab" data-bs-target="#app-tab-pane"
                type="button" role="tab" aria-controls="app-tab-pane" aria-selected="true">Aplikasi</button>
        </li>
        <li class="nav-item w-auto p-1" role="presentation">
            <button @class([
                'nav-link active' => $found == '2',
                'nav-link' => $found != '2',
            ]) id="setting-tab" data-bs-toggle="tab"
                data-bs-target="#setting-tab-pane" type="button" role="tab" aria-controls="setting-tab-pane"
                aria-selected="false">Pengaturan</button>
        </li>
        <li class="nav-item w-auto p-1" role="presentation">
            <button @class([
                'nav-link active' => $found == '3',
                'nav-link' => $found != '3',
            ]) id="profile-tab" data-bs-toggle="tab"
                data-bs-target="#profile-tab-pane" type="button" role="tab" aria-controls="profile-tab-pane"
                aria-selected="false">Profil</button>
        </li>
    </ul>
    <!-- APP MENU -->
    <div class="tab-content" id="myTabContent">
        <div @class([
            'tab-pane fade show active' => $found == '1',
            'tab-pane fade' => $found != '1',
        ]) id="app-tab-pane" role="tabpanel" aria-labelledby="app-tab" tabindex="0">
            <div class="list-group">
                @foreach (Menu::get_menus('1') as $r)
                    <a href="{{ $r->url }}" class="list-group-item list-group-flush border-none rounded my-1">
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
        <!-- SETTING MENU -->
        <div @class([
            'tab-pane fade show active' => $found == '2',
            'tab-pane fade' => $found != '2',
        ]) id="setting-tab-pane" role="tabpanel" aria-labelledby="setting-tab"
            tabindex="0">
            <div class="list-group">
                @foreach (Menu::get_menus('2') as $r)
                    <a href="{{ $r->url }}" class="list-group-item list-group-flush border-none rounded my-1">
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
        <!-- PROFILE MENU -->
        <div @class([
            'tab-pane fade show active' => $found == '3',
            'tab-pane fade' => $found != '3',
        ]) id="profile-tab-pane" role="tabpanel" aria-labelledby="profile-tab"
            tabindex="0">
            <div class="list-group">
                @foreach (Menu::get_menus('3') as $r)
                    <a href="{{ $r->url }}" class="list-group-item list-group-flush border-none rounded my-1">
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

    </div>
    <div class="list-group sticky-bottom">
        <a href="{{ url('logout') }}" class="list-group-item list-group-flush border-none rounded my-1">
            <div class="d-flex justify-content-between">
                <div>
                    <i class="fa-solid fa-right-from-bracket"></i> Keluar
                </div>
                <div>
                    &nbsp;
                </div>
            </div>
        </a>
    </div>
</div>

@php
    use App\Helpers\Menu;
@endphp
<div class="container side-height">
    <h1 class="text-center my-5 fw-bold py-3 px-1">
        <a href="/" class="text-decoration-none">{{ env('APP_NAME') }}</a>
    </h1>
    <ul class="nav nav-tabs" id="myTab" role="tablist">
        <li class="nav-item w-auto p-1" role="presentation">
            <button class="nav-link active" id="app-tab" data-bs-toggle="tab" data-bs-target="#app-tab-pane"
                type="button" role="tab" aria-controls="app-tab-pane" aria-selected="true">Aplikasi</button>
        </li>
        <li class="nav-item w-auto p-1" role="presentation">
            <button class="nav-link" id="setting-tab" data-bs-toggle="tab" data-bs-target="#setting-tab-pane"
                type="button" role="tab" aria-controls="setting-tab-pane" aria-selected="false">Pengaturan</button>
        </li>
        <li class="nav-item w-auto p-1" role="presentation">
            <button class="nav-link" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile-tab-pane"
                type="button" role="tab" aria-controls="profile-tab-pane" aria-selected="false">Profil</button>
        </li>
    </ul>
    <!-- APP MENU -->
    <div class="tab-content" id="myTabContent">
        <div class="tab-pane fade show active" id="app-tab-pane" role="tabpanel" aria-labelledby="app-tab"
            tabindex="0">
            <div class="list-group">
                @foreach (Menu::get_menus('app') as $r)
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
        <div class="tab-pane fade" id="setting-tab-pane" role="tabpanel" aria-labelledby="setting-tab" tabindex="0">
            On development
        </div>
        <!-- PROFILE MENU -->
        <div class="tab-pane fade" id="profile-tab-pane" role="tabpanel" aria-labelledby="profile-tab" tabindex="0">
            On development
        </div>
    </div>


</div>

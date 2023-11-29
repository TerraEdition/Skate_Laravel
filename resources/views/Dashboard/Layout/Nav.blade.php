@php
    $menu = Menu::get_menu_active();
@endphp
<div class="container py-5">
    <div class="">
        <small>Halaman / {{ $menu->menu }}</small>
        <div class="d-flex justify-content-between">
            <h1>{{ ($menu->id == 1 ? 'Main ' : '') . $menu->menu }}</h1>
            <div class="bg-white border-3 rounded-pill">
                <div class="row m-auto my-2">
                    <div class="col-7 m-auto">
                        <span class="input-group rounded-pill">
                            <span class="input-group-text" id="inputGroup-sizing-sm"><i
                                    class="fa-solid fa-magnifying-glass"></i></span>
                            <input type="text" class="form-control" id="search" autocomplete="off">
                        </span>
                    </div>
                    <div class="col-1 m-auto">
                        <i class="fa-regular fa-bell fa-lg"></i>
                    </div>
                    <div class="col-1 m-auto">
                        <i class="fa-solid fa-moon fa-lg"></i>
                    </div>
                    <div class="col-2 m-auto">
                        <img src="<?= asset('storage/image/profile/default.png') ?>" alt="profile"
                            class="rounded-circle" style="width:50px">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

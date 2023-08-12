<?php

namespace App\Helpers;

use App\Models\Menu as MenuModel;

class Menu
{
    public static function get_menus()
    {
        return MenuModel::orderBy('id', 'asc')->get();
    }

    public static function get_menu_active()
    {
        return MenuModel::where('url', 'like', '%' . request()->path() . '%')->first();
    }
}

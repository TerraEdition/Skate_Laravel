<?php

namespace App\Helpers;

use App\Models\Menu as MenuModel;

class Menu
{
    public static function get_menus($type = 'app')
    {
        return MenuModel::orderBy('id', 'asc')->where('type', $type)->get();
    }

    public static function get_menu_active()
    {
        $menu =  MenuModel::where(function ($query) {
            $key = explode('/', Format::clean_char_search(request()->path()));
            $query->where(function ($query) use ($key) {
                $query->orWhere('url', 'like', '%' . $key[0] . '%');
            });
        });

        return $menu->first();
    }
}

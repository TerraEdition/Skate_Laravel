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
        $key = explode('/', Format::clean_char_search(request()->path()));
        # member tidak ada di menu
        if (in_array('member', $key)) {
            return (object)['id' => '0', 'menu' => 'Anggota'];
        }

        $menu =  MenuModel::where(function ($query) use ($key) {
            $query->where(function ($query) use ($key) {
                $query->orWhere('url', 'like', '%' . $key[0] . '%');
            });
        });

        return $menu->first();
    }
}

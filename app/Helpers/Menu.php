<?php

namespace App\Helpers;

use App\Models\Menu as MenuModel;

class Menu
{
    public static function get_menus($type = false)
    {
        $result = MenuModel::orderBy('id', 'asc');
        if ($type) {
            $result->where('tab_id', $type);
        }
        return $result->get();
    }

    public static function get_menu_active()
    {
        $key = explode('/', Format::clean_char_search(request()->path()));
        # member tidak ada di menu
        if (in_array('member', $key)) {
            return (object)['id' => '0', 'menu' => 'Anggota'];
        } else if (in_array('participant', $key)) {
            return (object)['id' => '0', 'menu' => 'Peserta'];
        } else if (in_array('group', $key)) {
            return (object)['id' => '0', 'menu' => 'Grup'];
        } else if (in_array('password', $key)) {
            return (object)['id' => '0', 'menu' => 'Ganti Password'];
        }

        $menu =  MenuModel::where(function ($query) use ($key) {
            $query->where(function ($query) use ($key) {
                $query->orWhere('url', 'like', '%' . $key[0] . '%');
            });
        });

        return $menu->first();
    }
}

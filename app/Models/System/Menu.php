<?php

namespace App\Models\System;

use CodeIgniter\Model;

class Menu extends Model
{
    protected $table            = 'menus';
    protected $primaryKey       = 'id';
    protected $returnType       = 'object';
    protected $useTimestamps    = true;

    function getMenuSidebar() {
        $sql = "SELECT * 
                FROM menus 
                where active = 1 ";

        $menus = $this->query($sql)->getResult();

        if(!empty($menus)) {
            foreach($menus as $i => $menu) {
                $sql_item = "SELECT * 
                            FROM items_menu im 
                            WHERE im.active = 1 
                            AND im.id_menu = $menu->id
                            ORDER BY im.id ASC
                        ";
                $items = $this->query($sql_item)->getResult();
                
                if(!empty($items)) {
                    $menus[$i]->submenu = $items;
                }
            }
        }

        return $menus;
    }
}

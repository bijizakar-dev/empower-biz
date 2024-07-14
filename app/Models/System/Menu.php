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
        $user_role_session = session()->get('id_role') ? session()->get('id_role') : NULL; 

        $q = '';
       
        $sql = "SELECT * 
                FROM menus 
                where active = 1 ";

        $menus = $this->query($sql)->getResult();

        if(!empty($menus)) {
            foreach($menus as $i => $menu) {
                if($user_role_session != NULL) {
                    $q = " AND imp.id_role = $user_role_session AND imp.active = 1";
                }
        
                $sql_item = "SELECT * 
                            FROM items_menu im 
                            JOIN item_menu_permissions imp ON (im.id = imp.id_item_menu)
                            WHERE im.active = 1 
                            AND im.id_menu = $menu->id
                            $q 
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

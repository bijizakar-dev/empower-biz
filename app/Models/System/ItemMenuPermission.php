<?php

namespace App\Models\System;

use CodeIgniter\Model;

class ItemMenuPermission extends Model
{
    protected $table            = 'item_menu_permissions';
    protected $primaryKey       = 'id';
    protected $returnType       = 'object';
    protected $allowedFields    = ['id_item_menu', 'id_role', 'add', 'edit', 'delete', 'detail_view', 'export', 'import', 'active'];
    protected $useTimestamps    = true;

    function get_permission_role($id) {
        $sql = "SELECT imp.*, im.name as name_item_menu, m.name as name_menu
                FROM item_menu_permissions imp
                JOIN items_menu im ON imp.id_item_menu = im.id
                JOIN menus m ON im.id_menu = m.id
                JOIN roles r ON imp.id_role = r.id
                WHERE imp.id_role = $id
                AND m.active = 1 
                AND im.active = 1
                AND r.active = 1
                ORDER BY im.id ASC ";

        $data = $this->query($sql)->getResult();

        return $data;
            
    }
}

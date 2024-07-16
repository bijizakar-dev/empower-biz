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
        $sql = "SELECT im.id AS id_item_menu,
                    im.name AS name_item_menu,
                    m.name AS name_menu,
                    r.id as id_role,
                    COALESCE(imp.active, 0) AS active,
                    COALESCE(imp.`add`, 0) AS `add`,
                    COALESCE(imp.edit, 0) AS edit,
                    COALESCE(imp.`delete`, 0) AS `delete`,
                    COALESCE(imp.detail_view, 0) AS detail_view,
                    COALESCE(imp.`import`, 0) AS `import`,
                    COALESCE(imp.`export`, 0) AS `export`
                FROM items_menu im
                JOIN menus m ON im.id_menu = m.id
                LEFT JOIN item_menu_permissions imp ON imp.id_item_menu = im.id AND imp.id_role = $id
                JOIN roles r ON r.id = $id
                WHERE m.active = 1
                    AND im.active = 1
                    AND r.active = 1
                ORDER BY im.id ASC";

        $data = $this->query($sql)->getResult();

        return $data;    
    }

    function get_permission_by_role_id($id_role) {
        $sql = "SELECT id 
                FROM item_menu_permissions 
                WHERE id_role = $id_role ";

        $data = $this->query($sql)->getResult();

        return $data;    
    }

    function get_permission_rules($id_role, $path_item_menu) {
        $sql = "SELECT im.id AS id_item_menu,
                    im.name AS name_item_menu,
                    m.name AS name_menu,
                    r.id as id_role,
                    COALESCE(imp.active, 0) AS active,
                    COALESCE(imp.`add`, 0) AS `add`,
                    COALESCE(imp.edit, 0) AS edit,
                    COALESCE(imp.`delete`, 0) AS `delete`,
                    COALESCE(imp.detail_view, 0) AS detail_view,
                    COALESCE(imp.`import`, 0) AS `import`,
                    COALESCE(imp.`export`, 0) AS `export` 
                FROM items_menu im
                JOIN menus m ON im.id_menu = m.id
                LEFT JOIN item_menu_permissions imp ON imp.id_item_menu = im.id AND imp.id_role = $id_role
                JOIN roles r ON r.id = $id_role
                WHERE m.active = 1
                    AND im.active = 1
                    AND r.active = 1
                    AND im.path = '".$path_item_menu."' 
                LIMIT 1";

        $data = $this->query($sql)->getResult();

        return $data;    
    }
}

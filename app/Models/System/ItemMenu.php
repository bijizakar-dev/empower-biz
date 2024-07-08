<?php

namespace App\Models\System;

use CodeIgniter\Model;

class ItemMenu extends Model
{
    protected $table            = 'items_menu';
    protected $primaryKey       = 'id';
    protected $returnType       = 'object';
    protected $allowedFields    = ['name', 'path', 'icon', 'active'];
    protected $useTimestamps    = true;

}

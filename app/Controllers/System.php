<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class System extends BaseController
{
    public function getRole() {
        $data['title'] = "Role";

        return view('system/role', $data);
    }
}

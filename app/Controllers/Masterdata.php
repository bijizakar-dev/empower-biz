<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\Masterdata\Department;
use CodeIgniter\HTTP\ResponseInterface;

class Masterdata extends BaseController
{
    function __construct() {
        $this->m_department = new Department();
    }

    public function getDepartment() {
        $data['title'] = "Department";

        return view('masterdata/department', $data);
    }
}

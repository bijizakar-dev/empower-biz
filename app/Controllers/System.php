<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\Masterdata\Employee;
use App\Models\System\Role;
use CodeIgniter\HTTP\ResponseInterface;

class System extends BaseController
{

    function __construct() {
        $this->m_employee = new Employee();
        $this->m_role = new Role();
    }

    public function getRole() {
        $data['title'] = "Role";

        return view('system/role', $data);
    }

    public function getUser() {
        $data['title'] = "User";
        $data['employee'] =  $this->m_employee->get_all_employee();
        $data['role'] =  $this->m_role->get_all_role();

        return view('system/user', $data);
    }
}

<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\Masterdata\Department;
use App\Models\System\ItemMenuPermission;
use CodeIgniter\HTTP\ResponseInterface;

class Masterdata extends BaseController
{
    function __construct() {
        $this->m_department = new Department();
        $this->m_permission = new ItemMenuPermission();

        $this->id_role_session = session()->get('id_role') ? session()->get('id_role') : NULL; 
        if($this->id_role_session == NULL) {
            return false;
        }
    }

    public function getDepartment() {
        $data['title'] = "Department";

        // Ambil id_role yang sedang login + path url menunya
        $data['permissions'] = $this->m_permission->get_permission_rules($this->id_role_session, 'masterdata/department'); 
        
        return view('masterdata/department', $data);
    }

    public function getWarehouse() {
        $data['title'] = "Warehouse";

        return view('masterdata/warehouse', $data);
    }

    public function getUnit() {
        $data['title'] = "Satuan";

        return view('masterdata/unit', $data);
    }

    public function getSupplier() {
        $data['title'] = "Supplier";

        return view('masterdata/supplier', $data);
    }

    public function getEmployee() {
        $data['title'] = "Pegawai";
        $data['department'] = $this->m_department->get_all_department();

        return view('masterdata/employee', $data);
    }
}

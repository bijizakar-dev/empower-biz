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

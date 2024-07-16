<?php

namespace App\Controllers\Api;

use App\Models\Masterdata\Department;
use App\Models\Masterdata\Employee;
use App\Models\Masterdata\Supplier;
use App\Models\Masterdata\SupplierContact;
use App\Models\Masterdata\Unit;
use App\Models\Masterdata\Item;
use App\Models\Masterdata\Warehouse;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;

class Masterdata extends ResourceController
{
    function __construct() {
        $this->limit = 10;
        $this->m_department = new Department();
        $this->m_warehouse = new Warehouse();
        $this->m_unit = new Unit();
        $this->m_supplier = new Supplier();
        $this->m_supplier_contact = new SupplierContact();
        $this->m_employee = new Employee();
        $this->m_item = new Item();
    }

    private function start($page){
        return (($page - 1) * $this->limit);
    }

    /* DEPARTMENTS */
    public function getListDepartment(): ResponseInterface {
        // if(!$this->request->getVar('page')){
        //     return $this->respond(NULL, 400);
        // }
        
        $search = array(
            'search' => $this->request->getVar('search')
        );

        $start = $this->start($this->request->getVar('page'));

        $data = $this->m_department->get_list_department($this->limit, $start, $search);
        $data['page'] = (int)$this->request->getVar('page');
        $data['limit'] = $this->limit;
        
        if($data){
            return $this->respond($data, 200); 
        }else{
            return $this->respond(array('error' => 'Data tidak ditemukan'), 404);
        }
    }

    public function getDepartment(): ResponseInterface {
        if(!$this->request->getVar('id')){
            return $this->respond(NULL, 400);
        }

        $data['data'] = $this->m_department->get_department($this->request->getVar('id'));
        $data['page'] = 1;
        $data['limit'] = $this->limit;

        if($data){
            return $this->respond($data, 200); 
        }else{
            return $this->respond(array('error' => 'Data tidak ditemukan'), 404);
        }
    }

    public function postDepartment(): ResponseInterface {
        $id = null;
        if($this->request->getPost('id')){
            $id = $this->request->getPost('id');
        }

        $add = array (
            'id' => $id,
            'name' => $this->request->getPost('name'),
            'description' => $this->request->getPost('description'),
            'active' => $this->request->getPost('active')
        );

        $data = $this->m_department->update_department($add);

        return $this->respond($data, 200);
    }

    public function deleteDepartment(): ResponseInterface {
        if(!$this->request->getVar('id')){
            return $this->respond(NULL, 400);
        }

        $result = $this->m_department->delete_department($this->request->getVar('id'));

        if($result){
            return $this->respond(array('status' => $result), 200); 
        }else{
            return $this->respond(array('status' => false), 200);
        }
    }
    /* DEPARTMENTS */

    /* WAREHOUSE */
    public function getListWarehouse(): ResponseInterface {
        // if(!$this->request->getVar('page')){
        //     return $this->respond(NULL, 400);
        // }
        
        $search = array(
            'search' => $this->request->getVar('search')
        );

        $start = $this->start($this->request->getVar('page'));

        $data = $this->m_warehouse->get_list_warehouse($this->limit, $start, $search);
        $data['page'] = (int)$this->request->getVar('page');
        $data['limit'] = $this->limit;
        
        if($data){
            return $this->respond($data, 200); 
        }else{
            return $this->respond(array('error' => 'Data tidak ditemukan'), 404);
        }
    }

    public function getWarehouse(): ResponseInterface {
        if(!$this->request->getVar('id')){
            return $this->respond(NULL, 400);
        }

        $data['data'] = $this->m_warehouse->get_warehouse($this->request->getVar('id'));
        $data['page'] = 1;
        $data['limit'] = $this->limit;

        if($data){
            return $this->respond($data, 200); 
        }else{
            return $this->respond(array('error' => 'Data tidak ditemukan'), 404);
        }
    }

    public function postWarehouse(): ResponseInterface {
        $id = null;
        if($this->request->getPost('id')){
            $id = $this->request->getPost('id');
        }

        $add = array (
            'id' => $id,
            'name' => $this->request->getPost('name'),
            'description' => $this->request->getPost('description'),
            'active' => $this->request->getPost('active')
        );

        $data = $this->m_warehouse->update_warehouse($add);

        return $this->respond($data, 200);
    }

    public function deleteWarehouse(): ResponseInterface {
        if(!$this->request->getVar('id')){
            return $this->respond(NULL, 400);
        }

        $result = $this->m_warehouse->delete_warehouse($this->request->getVar('id'));

        if($result){
            return $this->respond(array('status' => $result), 200); 
        }else{
            return $this->respond(array('status' => false), 200);
        }
    }
    /* WAREHOUSE */

    /* UNIT */
    public function getListUnit(): ResponseInterface {
        // if(!$this->request->getVar('page')){
        //     return $this->respond(NULL, 400);
        // }
        
        $search = array(
            'search' => $this->request->getVar('search')
        );

        $start = $this->start($this->request->getVar('page'));

        $data = $this->m_unit->get_list_unit($this->limit, $start, $search);
        $data['page'] = (int)$this->request->getVar('page');
        $data['limit'] = $this->limit;
        
        if($data){
            return $this->respond($data, 200); 
        }else{
            return $this->respond(array('error' => 'Data tidak ditemukan'), 404);
        }
    }

    public function getUnit(): ResponseInterface {
        if(!$this->request->getVar('id')){
            return $this->respond(NULL, 400);
        }

        $data['data'] = $this->m_unit->get_unit($this->request->getVar('id'));
        $data['page'] = 1;
        $data['limit'] = $this->limit;

        if($data){
            return $this->respond($data, 200); 
        }else{
            return $this->respond(array('error' => 'Data tidak ditemukan'), 404);
        }
    }

    public function postUnit(): ResponseInterface {
        $id = null;
        if($this->request->getPost('id')){
            $id = $this->request->getPost('id');
        }

        $add = array (
            'id' => $id,
            'name' => $this->request->getPost('name'),
            'description' => $this->request->getPost('description'),
            'active' => $this->request->getPost('active')
        );

        $data = $this->m_unit->update_unit($add);

        return $this->respond($data, 200);
    }

    public function deleteUnit(): ResponseInterface {
        if(!$this->request->getVar('id')){
            return $this->respond(NULL, 400);
        }

        $result = $this->m_unit->delete_unit($this->request->getVar('id'));

        if($result){
            return $this->respond(array('status' => $result), 200); 
        }else{
            return $this->respond(array('status' => false), 200);
        }
    }
    /* UNIT */

    /* SUPPLIERS */
    public function getListSupplier(): ResponseInterface {
        // if(!$this->request->getVar('page')){
        //     return $this->respond(NULL, 400);
        // }
        
        $search = array(
            'search' => $this->request->getVar('search')
        );

        $start = $this->start($this->request->getVar('page'));

        $data = $this->m_supplier->get_list_supplier($this->limit, $start, $search);
        $data['page'] = (int)$this->request->getVar('page');
        $data['limit'] = $this->limit;
        
        if($data){
            return $this->respond($data, 200); 
        }else{
            return $this->respond(array('error' => 'Data tidak ditemukan'), 404);
        }
    }

    public function getSupplier(): ResponseInterface {
        if(!$this->request->getVar('id')){
            return $this->respond(NULL, 400);
        }

        $data['data'] = $this->m_supplier->get_supplier($this->request->getVar('id'));
        $data['page'] = 1;
        $data['limit'] = $this->limit;

        if($data){
            return $this->respond($data, 200); 
        }else{
            return $this->respond(array('error' => 'Data tidak ditemukan'), 404);
        }
    }

    public function postSupplier(): ResponseInterface {
        $id = null;
        if($this->request->getPost('id')){
            $id = $this->request->getPost('id');
        }

        $add = array (
            'id' => $id,
            'name' => $this->request->getPost('name'),
            'address' => $this->request->getPost('address'),
            'active' => $this->request->getPost('active')
        );

        $data = $this->m_supplier->update_supplier($add);

        return $this->respond($data, 200);
    }

    public function deleteSupplier(): ResponseInterface {
        if(!$this->request->getVar('id')){
            return $this->respond(NULL, 400);
        }

        $result = $this->m_supplier->delete_supplier($this->request->getVar('id'));

        if($result){
            return $this->respond(array('status' => $result), 200); 
        }else{
            return $this->respond(array('status' => false), 200);
        }
    }
    /* SUPPLIERS */

    /* SUPPLIERS CONTACT */
    public function getListSupplierContact(): ResponseInterface {
        // if(!$this->request->getVar('page')){
        //     return $this->respond(NULL, 400);
        // }
        
        $search = array(
            'id_supplier' => $this->request->getVar('id_supplier')
        );

        $start = $this->start($this->request->getVar('page'));

        $data = $this->m_supplier_contact->get_list_supplier_contact($this->limit, $start, $search);
        $data['page'] = (int)$this->request->getVar('page');
        $data['limit'] = $this->limit;
        
        if($data){
            return $this->respond($data, 200); 
        }else{
            return $this->respond(array('error' => 'Data tidak ditemukan'), 404);
        }
    }

    public function getSupplierContact(): ResponseInterface {
        if(!$this->request->getVar('id')){
            return $this->respond(NULL, 400);
        }

        $data['data'] = $this->m_supplier_contact->get_supplier_contact($this->request->getVar('id'));
        $data['page'] = 1;
        $data['limit'] = $this->limit;

        if($data){
            return $this->respond($data, 200); 
        }else{
            return $this->respond(array('error' => 'Data tidak ditemukan'), 404);
        }
    }    

    public function postSupplierContact(): ResponseInterface {
        $id = null;
        if($this->request->getPost('id')){
            $id = $this->request->getPost('id');
        }

        $add = array (
            'id' => $id,
            'id_supplier' => $this->request->getPost('id_supplier'),
            'name' => $this->request->getPost('name'),
            'email' => $this->request->getPost('email'),
            'phone' => $this->request->getPost('phone'),
            'active' => $this->request->getPost('active')
        );

        $data = $this->m_supplier_contact->update_supplier_contact($add);

        return $this->respond($data, 200);
    }

    public function deleteSupplierContact(): ResponseInterface {
        if(!$this->request->getVar('id')){
            return $this->respond(NULL, 400);
        }

        $result = $this->m_supplier_contact->delete_supplier_contact($this->request->getVar('id'));

        if($result){
            return $this->respond(array('status' => $result), 200); 
        }else{
            return $this->respond(array('status' => false), 200);
        }
    }
    /* SUPPLIERS CONTACT */

    /* EMPLOYEES */
    public function getListEmployee(): ResponseInterface {
         // if(!$this->request->getVar('page')){
        //     return $this->respond(NULL, 400);
        // }

        $search = array(
            'search'            => $this->request->getVar('search'),
            'nip'               => $this->request->getVar('nip'),
            'name'              => $this->request->getVar('name'),
            'gender'            => $this->request->getVar('gender'),
            'birth_date'        => $this->request->getVar('birth_date'),
            'education'         => $this->request->getVar('education'),
            'id_department'     => $this->request->getVar('id_department'),
            'active'            => $this->request->getVar('active'),
        );

        $start = $this->start($this->request->getVar('page'));

        $data = $this->m_employee->get_list_employee($this->limit, $start, $search);
        $data['page'] = (int)$this->request->getVar('page');
        $data['limit'] = $this->limit;

        if($data){
            return $this->respond($data, 200); 
        }else{
            return $this->respond(array('error' => 'Data tidak ditemukan'), 404);
        }
    }

    public function getEmployee(): ResponseInterface {
        if(!$this->request->getVar('id')){
            return $this->respond(NULL, 400);
        }

        $data['data'] = $this->m_employee->get_employee($this->request->getVar('id'));
        $data['page'] = 1;
        $data['limit'] = $this->limit;

        if($data){
            return $this->respond($data, 200); 
        }else{
            return $this->respond(array('error' => 'Data tidak ditemukan'), 404);
        }
    }

    private function generateNIP($seq, $idDep, $joinYear): String {
        $seq = str_pad($seq, 3, '0', STR_PAD_LEFT);
        $idDep = str_pad($idDep, 2, '0', STR_PAD_LEFT);

        $nip = 'EB-'.$idDep.$joinYear.'-'.$seq;

        return $nip;
    }

    public function postEmployee(): ResponseInterface {
        $id = null;
        if($this->request->getPost('id')){
            $id = $this->request->getPost('id');
        }

        $add = array (
            'id' => $id,
            'nip' => $this->request->getPost('nip'),
            'name' => $this->request->getPost('name'),
            'gender' => $this->request->getPost('gender'),
            'birth_date' => $this->request->getPost('birth_date'),
            'phone_number' => $this->request->getPost('phone_number'),
            'address' => $this->request->getPost('address'),
            'education' => $this->request->getPost('education'),
            'id_department' => $this->request->getPost('id_department'),
            'photo' => null,
            'active' => $this->request->getPost('active')
        );

        $insEmployee = $this->m_employee->update_employee($add);

        // header('Content-Type: application/json');
        // die(json_encode($insEmployee));

        if($this->request->getPost('nip') == '' || $this->request->getPost('nip') == null) {
            if($insEmployee) {
                $createYear = date('Y-m-d');
                $nip = $this->generateNIP($insEmployee['id'], $this->request->getPost('id_department'), date('Y', strtotime($createYear)));
                $addNIP = array (
                    'id' => $insEmployee['id'],
                    'nip' => $nip,
                );
                $this->m_employee->update_employee($addNIP);
            }
        }
        

        return $this->respond($insEmployee, 200);
    }

    public function deleteEmployee(): ResponseInterface {
        if(!$this->request->getVar('id')){
            return $this->respond(NULL, 400);
        }

        $result = $this->m_employee->delete_employee($this->request->getVar('id'));

        if($result){
            return $this->respond(array('status' => $result), 200); 
        }else{
            return $this->respond(array('status' => false), 200);
        }
    }
    /* EMPLOYEES */

    /* ITEMS */
    public function getListItem(): ResponseInterface {
        // if(!$this->request->getVar('page')){
       //     return $this->respond(NULL, 400);
       // }

       $search = array(
           'search'           => $this->request->getVar('search'),
           'name'             => $this->request->getVar('name'),
           'code'             => $this->request->getVar('code'),
           'id_warehouse'     => $this->request->getVar('id_warehouse'),
           'id_unit'          => $this->request->getVar('id_unit'),
           'active'           => $this->request->getVar('active'),
       );

       $start = $this->start($this->request->getVar('page'));

       $data = $this->m_item->get_list_item($this->limit, $start, $search);
       $data['page'] = (int)$this->request->getVar('page');
       $data['limit'] = $this->limit;

       if($data){
           return $this->respond($data, 200); 
       }else{
           return $this->respond(array('error' => 'Data tidak ditemukan'), 404);
       }
    }

    public function getItem(): ResponseInterface {
        if(!$this->request->getVar('id')){
            return $this->respond(NULL, 400);
        }

        $data['data'] = $this->m_item->get_item($this->request->getVar('id'));
        $data['page'] = 1;
        $data['limit'] = $this->limit;

        if($data){
            return $this->respond($data, 200); 
        }else{
            return $this->respond(array('error' => 'Data tidak ditemukan'), 404);
        }
    }

    private function generateCodeItem($seq, $idWarehouse, $joinYear): String {
        $seq = str_pad($seq, 3, '0', STR_PAD_LEFT);
        $idWarehouse = str_pad($idWarehouse, 2, '0', STR_PAD_LEFT);

        $code = $idWarehouse.'/'.$joinYear.'-'.$seq;

        return $code;
    }

    public function postItem(): ResponseInterface {
        $id = null;
        if($this->request->getPost('id')){
            $id = $this->request->getPost('id');
        }

        $add = array (
            'id' => $id,
            'id_warehouse' => $this->request->getPost('id_warehouse'),
            'id_unit' => $this->request->getPost('id_unit'),
            'code' => $this->request->getPost('code'),
            'name' => $this->request->getPost('name'),
            'active' => $this->request->getPost('active')
        );

        $insItem = $this->m_item->update_item($add);

        // header('Content-Type: application/json');
        // die(json_encode($insEmployee));

        if($this->request->getPost('code') == '' || $this->request->getPost('code') == null) {
            if($insItem) {
                $createYear = date('Y-m-d');
                $code = $this->generateCodeItem($insItem['id'], $this->request->getPost('id_warehouse'), date('Y', strtotime($createYear)));
                $addCode = array (
                    'id' => $insItem['id'],
                    'code' => $code,
                );
                $this->m_item->update_item($addCode);
            }
        }
        
        return $this->respond($insItem, 200);
    }

    public function deleteItem(): ResponseInterface {
        if(!$this->request->getVar('id')){
            return $this->respond(NULL, 400);
        }

        $result = $this->m_item->delete_item($this->request->getVar('id'));

        if($result){
            return $this->respond(array('status' => $result), 200); 
        }else{
            return $this->respond(array('status' => false), 200);
        }
    }
    /* ITEMS */

}

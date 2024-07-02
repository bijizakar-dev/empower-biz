<?php

namespace App\Controllers\Api;

use App\Models\Masterdata\Department;
use App\Models\Masterdata\Supplier;
use App\Models\Masterdata\SupplierContact;
use App\Models\Masterdata\Unit;
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


}

<?php

namespace App\Controllers\Api;

use App\Models\System\ItemMenu;
use App\Models\System\ItemMenuPermission;
use App\Models\System\Role;
use App\Models\System\User;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;

class System extends ResourceController
{
    function __construct() {
        $this->limit = 10;
        $this->m_role = new Role();
        $this->m_user = new User();
        $this->m_item_menu = new ItemMenu();
        $this->m_item_menu_permission = new ItemMenuPermission();
    }
    
    private function start($page){
        return (($page - 1) * $this->limit);
    }

    /* ROLES */
    public function getListRole(): ResponseInterface {
        // if(!$this->request->getVar('page')){
        //     return $this->respond(NULL, 400);
        // }
        
        $search = array(
            'search' => $this->request->getVar('search')
        );

        $start = $this->start($this->request->getVar('page'));

        $data = $this->m_role->get_list_role($this->limit, $start, $search);
        $data['page'] = (int)$this->request->getVar('page');
        $data['limit'] = $this->limit;
        
        if($data){
            return $this->respond($data, 200); 
        }else{
            return $this->respond(array('error' => 'Data tidak ditemukan'), 404);
        }
    }

    public function getRole(): ResponseInterface {
        if(!$this->request->getVar('id')){
            return $this->respond(NULL, 400);
        }

        $data['data'] = $this->m_role->get_role($this->request->getVar('id'));
        $data['page'] = 1;
        $data['limit'] = $this->limit;

        if($data){
            return $this->respond($data, 200); 
        }else{
            return $this->respond(array('error' => 'Data tidak ditemukan'), 404);
        }
    }

    public function postRole(): ResponseInterface {
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

        $data = $this->m_role->update_role($add);

        if($id == null) {
            $itemMenu = $this->m_item_menu->findAll();
            $itemMenuPermission = [];
            
            foreach($itemMenu as $i => $item) {
                $itemMenuPermission[] = [
                    'id_item_menu' => $item->id,
                    'id_role' => $data['id'],
                    'add' => 0,
                    'edit' => 0,
                    'delete' => 0,
                    'detail_view' => 0,
                    'export' => 0,
                    'import' => 0,
                    'active' => 0,
                ];
            }
            $this->m_item_menu_permission->insertBatch($itemMenuPermission);
        }
        
        return $this->respond($data, 200);
    }

    public function deleteRole(): ResponseInterface {
        if(!$this->request->getVar('id')){
            return $this->respond(NULL, 400);
        }

        $result = $this->m_role->delete_role($this->request->getVar('id'));

        if($result){
            return $this->respond(array('status' => $result), 200); 
        }else{
            return $this->respond(array('status' => false), 200);
        }
    }

    public function getMenuPermissionRole(): ResponseInterface {
        if(!$this->request->getVar('id_role')){
            return $this->respond(NULL, 400);
        }

        $data['data'] = $this->m_item_menu_permission->get_permission_role($this->request->getVar('id_role'));

        if($data){
            return $this->respond($data, 200); 
        }else{
            return $this->respond(array('error' => 'Data tidak ditemukan'), 404);
        }
    }

    public function postSavePermissionRole(): ResponseInterface {
        $permission = $this->request->getPost('permission');

        $items = [];
        $data = [
            'status' => true,
            'message' => 'Role Permission Berhasil diperbarui'
        ];

        foreach($permission as $i => $item) {
            $items[] = [
                'id'            => $item['id'],
                'active'        => $item['active'],
                'add'           => $item['add'],
                'edit'          => $item['edit'],
                'delete'        => $item['delete'],
                'detail_view'   => $item['detail_view'],
                'import'        => $item['import'],
                'export'        => $item['export']
            ];
        }

        $result = $this->m_item_menu_permission->updateBatch($items, 'id');

        if (!$result) {
            $data['status'] = false;
            $data['message'] = "Role Permission Gagal diperbarui";
        }

        return $this->respond($data, 200);
    }
    /* ROLES */

    /* USER */
    public function getListUser(): ResponseInterface {
        // if(!$this->request->getVar('page')){
        //     return $this->respond(NULL, 400);
        // }

        $search = array(
            'search'            => $this->request->getVar('search'),
            'id_role'           => $this->request->getVar('id_role'),
            'active'            => $this->request->getVar('active')
        );

        $start = $this->start($this->request->getVar('page'));

        $data = $this->m_user->get_list_user($this->limit, $start, $search);
        $data['page'] = (int)$this->request->getVar('page');
        $data['limit'] = $this->limit;

        if($data){
            return $this->respond($data, 200); 
        }else{
            return $this->respond(array('error' => 'Data tidak ditemukan'), 404);
        }
    }

    public function getUser(): ResponseInterface {
        if(!$this->request->getVar('id')){
            return $this->respond(NULL, 400);
        }

        $data['data'] = $this->m_user->get_user($this->request->getVar('id'));
        $data['page'] = 1;
        $data['limit'] = $this->limit;

        if($data){
            return $this->respond($data, 200); 
        }else{
            return $this->respond(array('error' => 'Data tidak ditemukan'), 404);
        }
    }

    public function postUser(): ResponseInterface {
        $id = null;
        if($this->request->getPost('id')){
            $id = $this->request->getPost('id');
        }

        $add = array (
            'id' => $id,
            'username' => $this->request->getPost('username'),
            'email' => $this->request->getPost('email'),
            'password' => $this->request->getPost('password'),
            'id_employee' => $this->request->getPost('id_employee'),
            'id_role' => $this->request->getPost('id_role'),
            'active' => $this->request->getPost('active')
        );

        $data = $this->m_user->update_user($add);

        return $this->respond($data, 200);
    }

    public function deleteUser(): ResponseInterface {
        if(!$this->request->getVar('id')){
            return $this->respond(NULL, 400);
        }

        $result = $this->m_user->delete_user($this->request->getVar('id'));

        if($result){
            return $this->respond(array('status' => $result), 200); 
        }else{
            return $this->respond(array('status' => false), 200);
        }
    }
    
    /* USER */
}

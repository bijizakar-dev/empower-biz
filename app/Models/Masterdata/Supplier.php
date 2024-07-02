<?php

namespace App\Models\Masterdata;

use CodeIgniter\Model;

class Supplier extends Model
{
    protected $table            = 'suppliers';
    protected $primaryKey       = 'id';
    protected $returnType       = 'object';
    protected $allowedFields    = ['name', 'address', 'active', 'deleted_at'];
    protected $useTimestamps    = true;
    protected $useSoftDeletes   = true;

    function get_list_supplier($limit, $start, $search) {
        $q = '';
        // $limit = " limit $start , $limit";

        if ($search['search'] != '') {
            $q .= "AND name LIKE '%".$search['search']."%' OR name LIKE '%".$search['search']."%'";
        }

        $count = "SELECT COUNT(id) as count ";
        $select = "SELECT *";
        $sql = " FROM suppliers 
                WHERE deleted_at is null  
                $q order by name asc ";

        $query = $this->query($select.$sql);
        $result['data'] = $query->getResult();
        $result['jumlah'] = intval($this->query($count.$sql)->getRow()->count);

        return $result;
    }

    function get_supplier($id) {
        $sql = "SELECT * FROM suppliers WHERE id = $id ";
        return $this->query($sql)->getRow();
    }

    function update_supplier($data) {
        $data['success'] = false;
        $data['message'] = '';

        if ($data['id']) {
            // Update
            $data['success'] = true;
            $this->where('id', $data['id'])->update($data['id'], $data);
            $data['id'] = $data['id'];
            
        }else{
            // insert
            $data['success'] = true;
            $this->insert($data);
            $data['id'] = $this->insertID();
        }
        
        return $data;
    }

    function delete_supplier($id) {
        $data = array('deleted_at' => date('Y-m-d H:i:s'));
        
        $res = $this->where('id', $id)->update($id, $data);
        
        return $res;
    }
}

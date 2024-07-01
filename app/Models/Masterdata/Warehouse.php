<?php

namespace App\Models\Masterdata;

use CodeIgniter\Model;

class Warehouse extends Model
{
    protected $table            = 'warehouses';
    protected $primaryKey       = 'id';
    protected $returnType       = 'object';
    protected $allowedFields    = ['name', 'description', 'active', 'deleted_at'];
    protected $useTimestamps    = true;
    protected $useSoftDeletes   = true;

    function get_list_warehouse($limit, $start, $search) {
        $q = '';
        // $limit = " limit $start , $limit";

        if ($search['search'] != '') {
            $q .= "AND name LIKE '%".$search['search']."%' OR name LIKE '%".$search['search']."%'";
        }

        $count = "SELECT COUNT(id) as count ";
        $select = "SELECT *";
        $sql = " FROM warehouses 
                WHERE deleted_at is null  
                $q order by name asc ";

        $query = $this->query($select.$sql);
        $result['data'] = $query->getResult();
        $result['jumlah'] = $this->query($count.$sql)->getRow()->count;

        return $result;
    }

    function get_warehouse($id) {
        $sql = "SELECT * FROM warehouses WHERE id = $id ";
        return $this->query($sql)->getRow();
    }

    function update_warehouse($data) {
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

    function delete_warehouse($id) {
        $data = array('deleted_at' => date('Y-m-d H:i:s'));
        
        $res = $this->where('id', $id)->update($id, $data);
        
        return $res;
    }
}

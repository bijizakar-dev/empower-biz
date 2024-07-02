<?php

namespace App\Models\Masterdata;

use CodeIgniter\Model;

class SupplierContact extends Model
{
    protected $table            = 'supplier_contacts';
    protected $primaryKey       = 'id';
    protected $returnType       = 'object';
    protected $allowedFields    = ['id_supplier', 'name', 'email', 'phone', 'active', 'deleted_at'];
    protected $useTimestamps    = true;
    protected $useSoftDeletes   = true;

    function get_list_supplier_contact($limit, $start, $search) {
        $q = '';
        // $limit = " limit $start , $limit";

        if ($search['id_supplier'] != '') {
            $q .= "AND sc.id_supplier = '".$search['id_supplier']."' ";
        }

        $count = "SELECT COUNT(sc.id) as count ";
        $select = "SELECT sc.*, s.name as name_supplier, s.address as address_supplier ";
        $sql = " FROM supplier_contacts sc
                JOIN suppliers s ON (sc.id_supplier = s.id)
                WHERE sc.deleted_at is null  
                $q order by sc.name asc ";

        $query = $this->query($select.$sql);
        $result['data'] = $query->getResult();
        $result['jumlah'] = intval($this->query($count.$sql)->getRow()->count);

        return $result;
    }

    function get_supplier_contact($id) {
        $sql = "SELECT sc.*, s.name as name_supplier, s.address as address_supplier 
                FROM supplier_contacts sc
                JOIN suppliers s ON sc.id_supplier = s.id
                WHERE sc.id = $id ";

        return $this->query($sql)->getRow();
    }

    function update_supplier_contact($data) {
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

    function delete_supplier_contact($id) {
        $data = array('deleted_at' => date('Y-m-d H:i:s'));
        
        $res = $this->where('id', $id)->update($id, $data);
        
        return $res;
    }
}

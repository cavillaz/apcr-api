<?php

namespace App\Models;

use CodeIgniter\Model;

class ResidenteModel extends Model
{
    protected $DBGroup          = "default";
    protected $table            = 'tb_residente';
    protected $primaryKey       = 'id_residente';
    protected $useAutoIncrement = true;
    protected $insertID         = 0;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['identificacion', 'nombres_usuario', 'correo', 'torre', 'apartamento', 'celular', 'clave'];

    



    

    


   
    /* public function getDataProfile()
    {
        
        $query=$this->db->query('SELECT * FROM `parqueadero` WHERE 1;');
        $result=$query->getResult();
        return $result;  
    } */
}

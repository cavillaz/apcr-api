<?php

namespace App\Models;

use CodeIgniter\Model;

class ParqueaderosModel extends Model
{
    protected $DBGroup          = "default";
    protected $table            = 'tb_parqueaderos';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $insertID         = 0;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['numero_parqueadero', 'nombre_parqueadero', 'estado'];

    



    

    


   
    /* public function getDataProfile()
    {
        
        $query=$this->db->query('SELECT * FROM `parqueadero` WHERE 1;');
        $result=$query->getResult();
        return $result;  
    } */
}

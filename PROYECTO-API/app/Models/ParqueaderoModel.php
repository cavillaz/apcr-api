<?php

namespace App\Models;

use CodeIgniter\Model;

class ParqueaderoModel extends Model
{
    protected $DBGroup          = "default";
    protected $table            = 'tb_parqueadero';
    protected $primaryKey       = 'id_ubicacion';
    protected $useAutoIncrement = true;
    protected $insertID         = 0;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['identificacion', 'tipo_vehiculo', 'estado', 'placa_vehiculo'];

    



    

    


   
    /* public function getDataProfile()
    {
        
        $query=$this->db->query('SELECT * FROM `parqueadero` WHERE 1;');
        $result=$query->getResult();
        return $result;  
    } */
}

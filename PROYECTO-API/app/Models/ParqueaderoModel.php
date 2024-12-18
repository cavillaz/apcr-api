<?php

namespace App\Models;

use CodeIgniter\Model;

class ParqueaderoModel extends Model
{
    protected $DBGroup          = "default";
    protected $table            = 'tb_historial_parqueaderos';
    protected $primaryKey       = 'parqueadero_id';
    protected $useAutoIncrement = true;
    protected $insertID         = 0;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['nombre_persona', 'documento_persona', 'tipo_vehiculo', 'placa_vehiculo', 'tipo_parqueadero', 'estado'];

    



    

    


   
    /* public function getDataProfile()
    {
        
        $query=$this->db->query('SELECT * FROM `parqueadero` WHERE 1;');
        $result=$query->getResult();
        return $result;  
    } */
}

<?php

namespace App\Models;

use CodeIgniter\Model;

class HistorialParqueaderosModel extends Model
{
    protected $DBGroup          = "default";
    protected $table            = 'tb_historial_parqueaderos';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $insertID         = 0;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['parqueadero_id','nombre_persona', 'documento_persona', 'tipo_vehiculo', 'placa_vehiculo', 'tipo_parqueadero', 'fecha_solicitud', 'estado'];

    



    

    


   
    /* public function getDataProfile()
    {
        
        $query=$this->db->query('SELECT * FROM `parqueadero` WHERE 1;');
        $result=$query->getResult();
        return $result;  
    } */
}

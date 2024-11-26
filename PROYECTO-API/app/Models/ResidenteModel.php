<?php

namespace App\Models;

use CodeIgniter\Model;

class ResidenteModel extends Model
{
    protected $DBGroup          = "default";
    protected $table            = 'tb_usuarios';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $insertID         = 0;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['correo', 'clave', 'nombre_completo', 'numero_documento', 'numero_celular', 'id_torre', 'id_apartamento', 'rol'];




    

    


   
    /* public function getDataProfile()
    {
        
        $query=$this->db->query('SELECT * FROM `parqueadero` WHERE 1;');
        $result=$query->getResult();
        return $result;  
    } */
}

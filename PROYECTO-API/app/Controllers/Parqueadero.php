<?php
namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\API\ResponseTrait;
use App\Models\ParqueaderoModel;

class Parqueadero extends BaseController
{
    
    
    public function index()
    {
        $parqueadero = new ParqueaderoModel();
        $data['parqueadero'] = $parqueadero->orderBy('id_ubicacion')->findAll();
        return json_encode($data);
    }

//Function update 

    public function update()
{   
    $result = [];
    try {
        $parqueadero = new ParqueaderoModel();
        
        $id_ubicacion = $this->request->getVar('id_ubicacion');
        
        if ($data['parqueadero'] = $parqueadero->where('id_ubicacion', $id_ubicacion)->first()) {
            
            $data = [
                'identificacion' => $this->request->getVar('identificacion'),
                'tipo_vehiculo' => $this->request->getVar('tipo_vehiculo'),
                'estado' => $this->request->getVar('estado'),
                'placa_vehiculo' => $this->request->getVar('placa_vehiculo'),
            ];

            $parqueadero->update($id_ubicacion, $data);

            $result["message"] = 'Updated successfully';
            $result["state"] = 200;
            $result["data"] = $data;
        } else {
            
            $result["message"] = 'Error updating: id_ubicacion is required';
            $result["state"] = 400; 
            $result["data"] = null;
            $result["error"] = 'id_ubicacion is required';
        }
    } catch (Exception $e) {
        $result["message"] = 'Error updating';
        $result["state"] = 500;
        $result["data"] = null;
        $result["error"] = $e->getMessage();
    }
    return json_encode($result);
}

        
}
<?php
namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\API\ResponseTrait;
use App\Models\ResidenteModel;

class Residente extends BaseController
{
    
    
    public function index()
    {
        $residente = new ResidenteModel();
        $data['tb_residente'] = $residente->orderBy('identificacion')->findAll();
        return json_encode($data);
    }


//Function insert
    public function insert()
    {
        $rules = [
            'identificacion' => ['rules' => 'required|is_unique[tb_residente.identificacion]'],
            'correo' => ['rules' => 'required|is_unique[tb_residente.correo]'],
                 
        ];

        if($this->validate($rules)){
            $model = new ResidenteModel();
            $data = [
                'identificacion' => $this->request->getVar('identificacion'),
                'nombres_usuario' => $this->request->getVar('nombres_usuario'),
                'correo' => $this->request->getVar('correo'),
                'torre' => $this->request->getVar('torre'),
                'apartamento' => $this->request->getVar('apartamento'),
                'celular' => $this->request->getVar('celular'),
                'clave' => password_hash($this->request->getVar('clave'), PASSWORD_DEFAULT),
                
            ];
            $model->insert($data);

            return ('Registered Successfully');
        }else{
            return ('Invalid Inputs: Residente already exist, change identificacion or correo');
            
        }
    }


//Function Update 
    public function update()
{   
    $result = [];
    try {
        $residente = new ResidenteModel();
        
        $id_residente = $this->request->getVar('id_residente');
        
        if ($data['tb_residente'] = $residente->where('id_residente', $id_residente)->first()) {
            
            $data = [
                'id_residente' => $this->request->getVar('id_residente'),
                'identificacion' => $this->request->getVar('identificacion'),
                'nombres_usuario' => $this->request->getVar('nombres_usuario'),
                'correo' => $this->request->getVar('correo'),
                'torre' => $this->request->getVar('torre'),
                'apartamento' => $this->request->getVar('apartamento'),
                'celular' => $this->request->getVar('celular'),
                'clave' => password_hash($this->request->getVar('clave'), PASSWORD_DEFAULT),
                
            ];

            $residente->update($id_residente, $data);

            $result["message"] = 'Updated successfully';
            $result["state"] = 200;
            $result["data"] = $data;
        } else {
            
            $result["message"] = 'Error updating: id_residente is required';
            $result["state"] = 400; 
            $result["data"] = null;
            $result["error"] = 'id_residente is required';
        }
    } catch (Exception $e) {
        $result["message"] = 'Error updating';
        $result["state"] = 500;
        $result["data"] = null;
        $result["error"] = $e->getMessage();
    }
    return json_encode($result);
}


    //Function delete 
    public function delete($identificacion = null)
    {
        $identificacion = $this->request->getVar('identificacion');

    if ($identificacion === null) {
        return 'Identificación is required';
    }

    $residenteModel = new ResidenteModel();

    try {
        
        $data = $residenteModel->where('identificacion', $identificacion)->first();
        if ($data) {
            
        $residenteModel->delete($data['id_residente']);
                return 'Residente eliminated';
            
        } else {
            return 'Residente not found';
        }
    } catch (\Exception $e) {
        
        return 'error: ' . $e->getMessage();
    }
}

}






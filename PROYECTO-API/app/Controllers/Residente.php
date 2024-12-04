<?php
namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\API\ResponseTrait;
use App\Models\ResidenteModel;
use CodeIgniter\RESTful\ResourceController;

class Residente extends ResourceController
{
    use ResponseTrait;

    public function index()
    {
        $residente = new ResidenteModel();
        $data['tb_usuarios'] = $residente->orderBy('id')->findAll();
        return $this->respond($data);
    }

    public function insert()
    {
        // Obtener los datos como JSON
        $input = $this->request->getJSON(true);
        log_message('debug', 'Datos recibidos: ' . json_encode($input));
    
        // Validar que los campos requeridos están presentes
        $requiredFields = ['correo', 'clave', 'nombre_completo', 'numero_documento', 'numero_celular'];
        foreach ($requiredFields as $field) {
            if (!isset($input[$field])) {
                return $this->response->setJSON([
                    'status' => 400,
                    'message' => "El campo '$field' es requerido y no fue enviado."
                ]);
            }
        }
    
        // Crear instancia del modelo
        $residenteModel = new \App\Models\ResidenteModel();
    
        try {
            // Insertar los datos
            $residenteModel->insert($input);
    
            return $this->response->setJSON([
                'status' => 201,
                'message' => 'Usuario registrado exitosamente.'
            ]);
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'status' => 500,
                'message' => 'Error al insertar usuario: ' . $e->getMessage()
            ]);
        }
    }
    
    
    

    public function update($id = null)
{
    $usuarioModel = new \App\Models\ResidenteModel();

    // Verificar si el usuario existe
    $usuario = $usuarioModel->find($id);
    if (!$usuario) {
        return $this->respond([
            'status' => 404,
            'message' => 'Usuario no encontrado.'
        ], 404);
    }

    // Obtener los datos del cuerpo de la solicitud
    $data = $this->request->getJSON(true);

    // Actualizar los campos necesarios
    $updateData = [
        'correo'           => $data['correo'],
        'nombre_completo'  => $data['nombre_completo'],
        'numero_documento' => $data['numero_documento'],
        'numero_celular'   => $data['numero_celular'],
    ];

    // Verificar si se proporciona una clave para actualizar
    if (!empty($data['clave'])) {
        $updateData['clave'] = password_hash($data['clave'], PASSWORD_DEFAULT);
    }

    if ($usuarioModel->update($id, $updateData)) {
        return $this->respond([
            'status' => 200,
            'message' => 'Usuario actualizado exitosamente.'
        ], 200);
    } else {
        return $this->respond([
            'status' => 500,
            'message' => 'Error al actualizar el usuario.'
        ], 500);
    }
}

    

public function delete($id = null)
{
    $usuarioModel = new \App\Models\ResidenteModel();

    // Verificar si el usuario existe
    $usuario = $usuarioModel->find($id);
    if (!$usuario) {
        return $this->respond([
            'status' => 404,
            'message' => 'Usuario no encontrado.',
        ], 404);
    }

    // Eliminar el usuario
    if ($usuarioModel->delete($id)) {
        return $this->respond([
            'status' => 200,
            'message' => 'Usuario eliminado correctamente.',
        ], 200);
    } else {
        return $this->respond([
            'status' => 500,
            'message' => 'Error al eliminar el usuario.',
        ], 500);
    }
}



}

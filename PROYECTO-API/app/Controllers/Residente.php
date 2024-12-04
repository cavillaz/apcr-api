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

    public function insert() {
        // Verifica que todos los datos requeridos estén presentes
        $input = $this->request->getPost();
    
        if (!$input['correo'] || !$input['clave'] || !$input['nombre_completo']) {
            return $this->response->setJSON([
                'status' => 400,
                'message' => 'Datos incompletos enviados al servidor.'
            ]);
        }
    
        // Lógica de inserción en la base de datos (ejemplo)
        $usuarioModel = new ResidenteModel();
    
        try {
            $usuarioModel->insert($input);
            return $this->response->setJSON([
                'status' => 201,
                'message' => 'Usuario registrado exitosamente.'
            ]);
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'status' => 500,
                'message' => 'Error interno del servidor: ' . $e->getMessage()
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

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
        $data = $this->request->getJSON(true);
        
        if (
            empty($data['correo']) ||
            empty($data['clave']) ||
            empty($data['nombre_completo']) ||
            empty($data['numero_documento']) ||
            empty($data['numero_celular'])
        ) {
            return $this->fail('Faltan datos obligatorios.', 400);
        }

        $usuarioModel = new ResidenteModel();
        $existeUsuario = $usuarioModel
            ->where('correo', $data['correo'])
            ->orWhere('numero_documento', $data['numero_documento'])
            ->first();

        if ($existeUsuario) {
            return $this->respond([
                'status' => 409,
                'message' => 'El correo o número de documento ya están registrados.',
            ], 409);
        }

        $nuevoUsuario = [
            'correo'           => $data['correo'],
            'clave'            => password_hash($data['clave'], PASSWORD_DEFAULT),
            'nombre_completo'  => $data['nombre_completo'],
            'numero_documento' => $data['numero_documento'],
            'numero_celular'   => $data['numero_celular'],
            'id_torre'         => $data['torre'] ?? null,
            'id_apartamento'   => $data['apartamento'] ?? null,
            'rol'              => $data['rol'] ?? 'residente',
        ];

        if ($usuarioModel->insert($nuevoUsuario)) {
            return $this->respondCreated(['message' => 'Usuario registrado exitosamente.']);
        } else {
            return $this->fail('Error al registrar el usuario.', 500);
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
        $usuarioModel = new ResidenteModel();

        if (!$usuarioModel->find($id)) {
            return $this->failNotFound('Usuario no encontrado.');
        }

        if ($usuarioModel->delete($id)) {
            return $this->respondDeleted(['message' => 'Usuario eliminado exitosamente.']);
        } else {
            return $this->fail('Error al eliminar el usuario.', 500);
        }
    }
}

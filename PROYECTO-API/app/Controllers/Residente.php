<?php
namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\API\ResponseTrait;
use App\Models\ResidenteModel;
use CodeIgniter\RESTful\ResourceController;

#class Residente extends BaseController
class Residente extends ResourceController
{
    
    
    public function index()
    {
        $residente = new ResidenteModel();
        $data['tb_usuarios'] = $residente->orderBy('id')->findAll();
        return json_encode($data);
    }

    public function insert()
    {
        // Obtener datos del cuerpo de la solicitud
        $data = $this->request->getJSON(true); // Convertir JSON a array asociativo
        
        // Validar campos obligatorios
        if (
            empty($data['correo']) ||
            empty($data['clave']) ||
            empty($data['nombre_completo']) ||
            empty($data['numero_documento']) ||
            empty($data['numero_celular'])
        ) {
            return $this->fail('Faltan datos obligatorios.', 400);
        }

        // Cargar el modelo
        $usuarioModel = new \App\Models\ResidenteModel();

        // Verificar si el correo o número de documento ya existen
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

        // Crear usuario
        $nuevoUsuario = [
            'correo'           => $data['correo'],
            'clave'            => password_hash($data['clave'], PASSWORD_DEFAULT),
            'nombre_completo'  => $data['nombre_completo'],
            'numero_documento' => $data['numero_documento'],
            'numero_celular'   => $data['numero_celular'],
            'id_torre'         => isset($data['torre']) ? $data['torre'] : null,
            'id_apartamento'   => isset($data['apartamento']) ? $data['apartamento'] : null,
            'rol'              => 'residente',
        ];

        if ($usuarioModel->insert($nuevoUsuario)) {
            return $this->respond([
                'status' => 201,
                'message' => 'Usuario registrado exitosamente.',
            ], 201);
        } else {
            return $this->respond([
                'status' => 500,
                'message' => 'Error al registrar el usuario.',
            ], 500);
        }
    }
}




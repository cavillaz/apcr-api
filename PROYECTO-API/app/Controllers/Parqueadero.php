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
        $data['parqueadero'] = $parqueadero->orderBy('parqueadero_id')->findAll();
        return json_encode($data);
    }

    public function solicitarParqueadero()
{
    // Obtén los datos enviados desde el cliente
    $data = $this->request->getJSON(true); // Usamos JSON para API
    $parqueadero_id = $data['parqueadero_id'];
    $nombre_persona = $data['nombre_persona'];
    $documento_persona = $data['documento_persona'];
    $tipo_vehiculo = $data['tipo_vehiculo'];
    $placa_vehiculo = $data['placa_vehiculo'];
    $tipo_parqueadero = $data['tipo_parqueadero'];

    // Cargar el modelo correspondiente
    $parqueaderosModel = new \App\Models\ParqueaderosModel(); // Modelo de `tb_historial_parqueaderos`
    $historialModel = new \App\Models\HistorialParqueaderosModel(); // Modelo para historial de parqueos

    // Verificar si el parqueadero ya está reservado o en uso
    $existingReservation = $historialModel->where('documento_persona', $documento_persona)
        ->orWhere('placa_vehiculo', $placa_vehiculo)
        ->whereIn('estado', ['pendiente_aprobacion', 'ocupado'])
        ->first();

    if ($existingReservation) {
        return $this->response->setJSON([
            'message' => 'Error: Ya tienes un parqueadero reservado o pendiente de aprobación',
            'state' => 400,
            'error' => 'Reserva existente'
        ]);
    }

    // Insertar nueva reserva en el historial
    $newReservationData = [
        'parqueadero_id' => $parqueadero_id,
        'nombre_persona' => $nombre_persona,
        'documento_persona' => $documento_persona,
        'tipo_vehiculo' => $tipo_vehiculo,
        'placa_vehiculo' => $placa_vehiculo,
        'tipo_parqueadero' => $tipo_parqueadero,
        'fecha_solicitud' => date('Y-m-d H:i:s'),
        'estado' => 'pendiente_aprobacion'
    ];

    if (!$historialModel->insert($newReservationData)) {
        return $this->response->setJSON([
            'message' => 'Error al reservar el parqueadero',
            'state' => 500,
            'error' => $historialModel->errors()
        ]);
    }

    // Actualizar el estado del parqueadero
    if (!$parqueaderosModel->update($parqueadero_id, ['estado' => 'pendiente_aprobacion'])) {
        return $this->response->setJSON([
            'message' => 'Error al actualizar el estado del parqueadero',
            'state' => 500,
            'error' => $parqueaderosModel->errors()
        ]);
    }

    // Respuesta exitosa
    return $this->response->setJSON([
        'message' => 'Parqueadero reservado correctamente',
        'state' => 200,
        'data' => $newReservationData
    ]);
}
        
}
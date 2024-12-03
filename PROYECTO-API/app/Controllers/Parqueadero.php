<?php
namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\API\ResponseTrait;
use App\Models\ParqueaderoModel;
use App\Models\HistorialParqueaderosModel;

class Parqueadero extends BaseController
{
    use ResponseTrait;

    public function index()
    {
        $parqueadero = new ParqueaderoModel();
        $data['parqueadero'] = $parqueadero->orderBy('parqueadero_id')->findAll();
        return $this->respond($data, 200);
    }

    public function solicitarParqueadero()
    {
        $data = $this->request->getJSON(true);
        $parqueadero_id = $data['parqueadero_id'] ?? null;
        $nombre_persona = $data['nombre_persona'] ?? null;
        $documento_persona = $data['documento_persona'] ?? null;
        $tipo_vehiculo = $data['tipo_vehiculo'] ?? null;
        $placa_vehiculo = $data['placa_vehiculo'] ?? null;
        $tipo_parqueadero = $data['tipo_parqueadero'] ?? null;

        $historialModel = new HistorialParqueaderosModel();
        $parqueaderosModel = new ParqueaderoModel();

        // Validar si ya existe una reserva para la misma persona o vehículo
        $existingReservation = $historialModel
            ->where('documento_persona', $documento_persona)
            ->orWhere('placa_vehiculo', $placa_vehiculo)
            ->whereIn('estado', ['pendiente_aprobacion', 'ocupado'])
            ->first();

        if ($existingReservation) {
            return $this->respond([
                'message' => 'Error: Ya tienes un parqueadero reservado o pendiente de aprobación.',
                'state' => 400,
                'error' => 'Reserva existente'
            ], 400);
        }

        // Asignar el próximo `parqueadero_id` consecutivo si el actual está ocupado
        $lastParqueadero = $historialModel->orderBy('parqueadero_id', 'DESC')->first();
        $nextParqueaderoId = $lastParqueadero ? $lastParqueadero['parqueadero_id'] + 1 : 1;

        $newReservationData = [
            'parqueadero_id' => $nextParqueaderoId,
            'nombre_persona' => $nombre_persona,
            'documento_persona' => $documento_persona,
            'tipo_vehiculo' => $tipo_vehiculo,
            'placa_vehiculo' => $placa_vehiculo,
            'tipo_parqueadero' => $tipo_parqueadero,
            'fecha_solicitud' => date('Y-m-d H:i:s'),
            'estado' => 'pendiente_aprobacion',
        ];

        if (!$historialModel->insert($newReservationData)) {
            return $this->respond([
                'message' => 'Error al reservar el parqueadero.',
                'state' => 500,
                'error' => $historialModel->errors()
            ], 500);
        }

        // Actualizar el estado del parqueadero en la tabla principal
        if (!$parqueaderosModel->update($parqueadero_id, ['estado' => 'pendiente_aprobacion'])) {
            return $this->respond([
                'message' => 'Error al actualizar el estado del parqueadero.',
                'state' => 500,
                'error' => $parqueaderosModel->errors()
            ], 500);
        }

        // Respuesta exitosa
        return $this->respond([
            'message' => 'Parqueadero reservado correctamente.',
            'state' => 200,
            'data' => $newReservationData
        ], 200);
    }
}

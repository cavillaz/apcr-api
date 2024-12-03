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
        $parqueaderoModel = new ParqueaderoModel();
        try {
            // Obtener todos los parqueaderos
            $data['parqueadero'] = $parqueaderoModel->orderBy('parqueadero_id')->findAll();
            return $this->respond($data, 200);
        } catch (\Exception $e) {
            return $this->failServerError('Error al obtener los datos de parqueaderos: ' . $e->getMessage());
        }
    }

    public function solicitarParqueadero()
    {
        try {
            $data = $this->request->getJSON(true);

            // Validar los datos requeridos
            $requiredFields = ['nombre_persona', 'documento_persona', 'tipo_vehiculo', 'placa_vehiculo', 'tipo_parqueadero'];
            foreach ($requiredFields as $field) {
                if (empty($data[$field])) {
                    return $this->failValidationErrors("El campo '$field' es obligatorio.");
                }
            }

            $nombre_persona = $data['nombre_persona'];
            $documento_persona = $data['documento_persona'];
            $tipo_vehiculo = $data['tipo_vehiculo'];
            $placa_vehiculo = $data['placa_vehiculo'];
            $tipo_parqueadero = $data['tipo_parqueadero'];

            $historialModel = new HistorialParqueaderosModel();
            $parqueaderosModel = new ParqueaderoModel();

            // Verificar si ya existe una reserva activa para el usuario o el vehículo
            $existingReservation = $historialModel
                ->groupStart()
                ->where('documento_persona', $documento_persona)
                ->orWhere('placa_vehiculo', $placa_vehiculo)
                ->groupEnd()
                ->whereIn('estado', ['pendiente_aprobacion', 'ocupado'])
                ->first();

            if ($existingReservation) {
                return $this->respond([
                    'message' => 'Error: Ya tienes un parqueadero reservado o pendiente de aprobación.',
                    'state' => 400,
                    'error' => 'Reserva existente'
                ], 400);
            }

            // Asignar el próximo `parqueadero_id` basado en el último existente
            $lastParqueadero = $historialModel->orderBy('parqueadero_id', 'DESC')->first();
            $nextParqueaderoId = $lastParqueadero ? $lastParqueadero['parqueadero_id'] + 1 : 1;

            // Crear una nueva reserva
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
                return $this->failServerError('Error al insertar la reserva: ' . implode(', ', $historialModel->errors()));
            }

            // Actualizar el estado del parqueadero en la tabla principal
            $parqueaderoToUpdate = $parqueaderosModel->where('parqueadero_id', $nextParqueaderoId)->first();
            if ($parqueaderoToUpdate) {
                if (!$parqueaderosModel->where('parqueadero_id', $nextParqueaderoId)->set(['estado' => 'pendiente_aprobacion'])->update()) {
                    return $this->failServerError('Error al actualizar el estado del parqueadero.');
                }
            }

            // Respuesta exitosa
            return $this->respond([
                'message' => 'Parqueadero reservado correctamente.',
                'state' => 200,
                'data' => $newReservationData
            ], 200);
        } catch (\Exception $e) {
            // Manejo de errores del servidor
            return $this->failServerError('Error al procesar la solicitud: ' . $e->getMessage());
        }
    }
}

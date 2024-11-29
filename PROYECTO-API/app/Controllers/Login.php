<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\API\ResponseTrait;
use App\Models\UserModel;
use \Firebase\JWT\JWT;

class Login extends BaseController
{
    use ResponseTrait;

    public function index()
    {
        // Configurar cabeceras CORS
        $this->setCorsHeaders();

        // Manejar solicitudes OPTIONS (preflight)
        if ($this->request->getMethod() === 'options') {
            return $this->respond(null, 200);
        }

        // Validación básica de entrada
        $email = $this->request->getVar('email');
        $password = $this->request->getVar('password');

        if (empty($email) || empty($password)) {
            return $this->respond(['error' => 'Email and password are required.'], 400);
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return $this->respond(['error' => 'Invalid email format.'], 400);
        }

        // Validar usuario y contraseña
        $userModel = new UserModel();
        $user = $userModel->where('email', $email)->first();

        if (is_null($user)) {
            return $this->respond(['error' => 'Invalid username or password.'], 401);
        }

        if (!password_verify($password, $user['password'])) {
            return $this->respond(['error' => 'Invalid username or password.'], 401);
        }

        // Generar token JWT
        $key = getenv('JWT_SECRET');
        $iat = time(); // Tiempo de emisión
        $exp = $iat + 3600; // Tiempo de expiración (1 hora)

        $payload = [
            "iss" => "Issuer of the JWT",
            "aud" => "Audience that the JWT",
            "sub" => "Subject of the JWT",
            "iat" => $iat,
            "exp" => $exp,
            "email" => $user['email'],
        ];

        $token = JWT::encode($payload, $key, 'HS256');

        // Responder con el token
        $response = [
            'message' => 'Login successful',
            'token' => $token
        ];

        return $this->respond($response, 200);
    }

    private function setCorsHeaders()
    {
        header("Access-Control-Allow-Origin: *"); // Cambiar "*" por el dominio del cliente en producción
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
        header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");
        header("Access-Control-Allow-Credentials: true"); // Si estás manejando cookies o tokens
    }
}

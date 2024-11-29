<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\API\ResponseTrait;
use App\Models\UserModel;
use \Firebase\JWT\JWT;

class Login extends BaseController
{
    use ResponseTrait;

    // Elimina setCorsHeaders() del archivo Login.php y ajusta así el método index:
public function index()
{
    file_put_contents('/tmp/debug.log', "Request method: " . $this->request->getMethod() . "\n", FILE_APPEND);
    file_put_contents('/tmp/debug.log', "Request data: " . json_encode($this->request->getJSON()) . "\n", FILE_APPEND);

    if ($this->request->getMethod() === 'options') {
        return $this->respond(null, 200);
    }

    $email = $this->request->getVar('email');
    $password = $this->request->getVar('password');

    if (empty($email) || empty($password)) {
        return $this->respond(['error' => 'Email and password are required.'], 400);
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return $this->respond(['error' => 'Invalid email format.'], 400);
    }

    $userModel = new UserModel();
    $user = $userModel->where('email', $email)->first();

    if (is_null($user)) {
        return $this->respond(['error' => 'Invalid username or password.'], 401);
    }

    if (!password_verify($password, $user['password'])) {
        return $this->respond(['error' => 'Invalid username or password.'], 401);
    }

    $key = getenv('JWT_SECRET');
    $iat = time();
    $exp = $iat + 3600;

    $payload = [
        "iss" => "Issuer of the JWT",
        "aud" => "Audience that the JWT",
        "sub" => "Subject of the JWT",
        "iat" => $iat,
        "exp" => $exp,
        "email" => $user['email'],
    ];

    $token = JWT::encode($payload, $key, 'HS256');

    $response = [
        'message' => 'Login successful',
        'token' => $token
    ];

    return $this->respond($response, 200);
}
}
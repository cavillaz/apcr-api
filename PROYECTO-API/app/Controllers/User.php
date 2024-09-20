<?php
namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\API\ResponseTrait;
use App\Models\UserModel;

class User extends BaseController
{
    use ResponseTrait;
    
    public function index()
    {
        $parqueadero = new UserModel;
        return $this->respond(['parqueadero' => $parqueadero->findAll()], 200);
        
    }

}





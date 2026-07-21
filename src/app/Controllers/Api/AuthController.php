<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Services\AuthService;

class AuthController extends BaseController
{
    protected AuthService $authService;
        
    public function __construct()
    {
        $this->authService = new AuthService();
    }

    public function register()
    {
        $data   = $this->request->getJSON(true);
        $result = $this->authService->register($data ?? []);
        if(!$result['success'])
        {
            return $this->response->setStatusCode(422)->setJSON([
                'message' => 'Validation failed.',
                'errors'  => $result['errors'],
            ]);
        }

        return $this->response->setStatusCode(201)->setJSON([
            'message' => 'Registration successful.',
            'user'    => $result['user'],
        ]);
    }

    public function login(){
        $data = $this->request->getJSON(true);
        $email = $data['email'] ?? '';
        $password = $data['password'] ?? '';

        $result = $this->authService->attemptLogin($email,$password);

        if(!$result['success'])
        {
            return $this->response->setStatusCode(401)->setJSON([
                'message' => $result['message'],
            ]);
        }

        return $this->response->setJSON([
            'message' => "Login successful.",
            'user'    => $result['user'],
        ]);


    }

    public function logout()
    {
        $user = $this->authService->currentUser();

        if(!$user)
        {
            return $this->response->setStatusCode(401)->setJSON([
                'message' => 'Not Authenticated',
            ]);
        }

        return $this->response->setJSON([
            'user' => $user,
        ]);
    }
}

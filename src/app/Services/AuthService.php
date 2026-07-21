<?php

namespace App\Services;

use App\Models\UserModel;
use Codeigniter\HTTP\IncomingRequest;

class AuthService
{
    protected UserModel $usermodel;
    
    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    public function register(array $data): array
    {
        $payload = [
            'username' => $data['username'] ?? '',
            'email'    => $data['email'] ?? '',
            'password' => $data['password'] ?? '',
        ];

        if(! $this->userModel->validate($payload)) 
        {
            return [
                'success' => false,
                'errors'  => $this->userModel->errors(),
                'user'    => null,
            ];
        }
        $payload['password'] = password_hash($payload['password'], PASSWORD_BCRYPT);
        
        $userId = $this->userModel->insert($payload, true);
        $user   = $this->userModel->find($userId);
        // Remove the password in the array to return to view
        unset($user['password']);

        return [
            'success' => true,
            'errors'  => null,
            'user'    => $user,
        ];
    }

    public function attemptLogin(string $email, string $password): array
    {
        // get email if existing on users table
        $user = $this->userModel->where('email', $email)->first();
        
        // non existing email or password incorrect
        if(!$user || ! password_verify($password, $user['password'])) 
        {
            return [
                'success' => false,
                'message' => 'Invalid email or password',
                'user'    => null,
            ];
        }

        // Remove the password in the array to return to view or store to session
        unset($user['password']);

        // Regenrate session ID on login to prevent session fixation
        session()->regenerate();

        session()->set([
            'user_id'     => $user['id'],
            'username'    => $user['username'],
            'isLoggedIn'  => true,
        ]);

        return [
            'success' => true,
            'message' => null,
            'user'    => $user,
        ];

    }

    public function logout(): void
    {
        session()->destroy();
    }

    public function currentUser(): ?array
    {
        if(!session()->get('isLoggedIn'))
        {
            return null;
        }

        $user = $this->userModel->find(session()->get('user_id'));
        if($user){
            unset($user['password']);
        }

        return $user;
    }
}
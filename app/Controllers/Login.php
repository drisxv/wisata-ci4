<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UserModel;
use App\Models\UserTokenModel;

class Login extends BaseController
{
    public function login()
    {
        $email    = trim($this->request->getVar('email') ?? '');
        $password = $this->request->getVar('password') ?? '';

        if ($email === '' || $password === '') {
            return $this->respond([
                'status'  => 'error',
                'message' => 'Email dan password wajib diisi'
            ], 400);
        }

        $userModel = new UserModel();
        $user      = $userModel->getByEmail($email);

        if (!$user || !password_verify($password, $user['password'])) {
            return $this->respond([
                'status'  => 'error',
                'message' => 'Email atau password salah'
            ], 401);
        }

        // generate token
        $token     = bin2hex(random_bytes(32));
        $expiresAt = date('Y-m-d H:i:s', strtotime('+1 day'));

        $tokenModel = new UserTokenModel();
        $tokenModel->storeToken($user['id'], $token, $expiresAt);

        return $this->respond([
            'status' => 'success',
            'message' => 'Login berhasil',
            'data' => [
                'access_token' => $token,
                'expires_at'   => $expiresAt,
                'user'         => [
                    'id'       => $user['id'],
                    'username' => $user['username'],
                    'email'    => $user['email'],
                    'role'     => $user['role'],
                ]
            ]
        ]);
    }

    private function respond($data, $statusCode = 200)
    {
        return $this->response->setJSON($data)->setStatusCode($statusCode);
    }
}

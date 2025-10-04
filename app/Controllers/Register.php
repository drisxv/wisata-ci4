<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UserModel;

class Register extends BaseController
{
    public function register()
    {
        $username = trim($this->request->getVar('username') ?? '');
        $email    = trim($this->request->getVar('email') ?? '');
        $password = $this->request->getVar('password') ?? '';
        $role     = $this->request->getVar('role') ?? 'user';

        if ($username === '' || $email === '' || $password === '') {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Username, email, dan password wajib diisi'
            ])->setStatusCode(400);
        }

        $userModel = new UserModel();

        if ($userModel->where('email', $email)->first()) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Email sudah terdaftar'
            ])->setStatusCode(409);
        }

        $userModel->insert([
            'username' => $username,
            'email'    => $email,
            'password' => password_hash($password, PASSWORD_DEFAULT),
            'role'     => $role
        ]);

        return $this->response->setJSON([
            'status'  => 'success',
            'message' => 'Registrasi berhasil'
        ]);
    }
}

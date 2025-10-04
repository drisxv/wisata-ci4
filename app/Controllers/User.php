<?php

namespace App\Controllers;

use App\Models\UserModel;
use CodeIgniter\RESTful\ResourceController;

class User extends ResourceController
{
    protected $modelName = UserModel::class;
    protected $format    = 'json';

    // GET /users
    public function index()
    {
        $data = $this->model->findAll();
        return $this->respond($data);
    }

    // GET /users/{id}
    public function show($id = null)
    {
        $user = $this->model->find($id);

        if (!$user) {
            return $this->failNotFound('User not found');
        }

        return $this->respond($user);
    }

    // POST /users
    public function create()
    {
        $data = [
            'username' => $this->request->getVar('username'),
            'email'    => $this->request->getVar('email'),
            'password' => password_hash($this->request->getVar('password'), PASSWORD_DEFAULT),
            'role'     => $this->request->getVar('role') ?? 'user',
        ];

        if (!$this->model->insert($data)) {
            return $this->fail($this->model->errors());
        }

        $data['id'] = $this->model->insertID();

        return $this->respondCreated($data);
    }

    // PUT /users/{id}
    public function update($id = null)
    {
        $updateData = [];

        if ($this->request->getVar('username')) {
            $updateData['username'] = $this->request->getVar('username');
        }

        if ($this->request->getVar('email')) {
            $updateData['email'] = $this->request->getVar('email');
        }

        if ($this->request->getVar('password')) {
            $updateData['password'] = password_hash($this->request->getVar('password'), PASSWORD_DEFAULT);
        }

        if ($this->request->getVar('role')) {
            $updateData['role'] = $this->request->getVar('role');
        }

        if (empty($updateData)) {
            return $this->fail('No data to update');
        }

        if (!$this->model->update($id, $updateData)) {
            return $this->fail($this->model->errors());
        }

        $updatedUser = $this->model->find($id);
        return $this->respondUpdated($updatedUser);
    }

    // DELETE /users/{id}
    public function delete($id = null)
    {
        $user = $this->model->find($id);

        if (!$user) {
            return $this->failNotFound('User not found');
        }

        $this->model->delete($id);

        return $this->respondDeleted(['id' => $id]);
    }
}

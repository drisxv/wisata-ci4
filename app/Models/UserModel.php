<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table            = 'users';
    protected $primaryKey       = 'id';
    protected $returnType       = 'array';
    protected $useSoftDeletes   = true;
    protected $allowedFields    = ['username', 'email', 'password', 'role'];
    protected $useTimestamps    = true;

    public function getByEmail($email)
    {
        return $this->where('email', $email)->first();
    }
}

<?php

namespace App\Models;

use CodeIgniter\Model;

class UserTokenModel extends Model
{
    protected $table            = 'user_tokens';
    protected $primaryKey       = 'id';
    protected $returnType       = 'array';
    protected $allowedFields    = ['user_id', 'token', 'expires_at', 'created_at'];

    public $useTimestamps = false;

    public function storeToken($userId, $token, $expiresAt)
    {
        return $this->insert([
            'user_id'    => $userId,
            'token'      => password_hash($token, PASSWORD_DEFAULT),
            'expires_at' => $expiresAt,
            'created_at' => date('Y-m-d H:i:s'),
        ]);
    }

    public function validateToken($token)
    {
        $rows = $this->findAll();
        foreach ($rows as $row) {
            if (password_verify($token, $row['token']) && $row['expires_at'] >= date('Y-m-d H:i:s')) {
                return $row['user_id'];
            }
        }
        return false;
    }
}

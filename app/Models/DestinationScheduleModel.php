<?php

namespace App\Models;

use CodeIgniter\Model;

class DestinationScheduleModel extends Model
{
    protected $table            = 'destination_schedules';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $allowedFields    = [
        'destination_id',
        'day',
        'open',
        'close',
        'created_at',
        'updated_at'
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    public function getByDestination($destinationId)
    {
        return $this->where('destination_id', $destinationId)->findAll();
    }
}

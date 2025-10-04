<?php

namespace App\Models;

use CodeIgniter\Model;

class DestinationModel extends Model
{
    protected $table      = 'destinations';
    protected $primaryKey = 'id';
    protected $useTimestamps = true;
    protected $returnType = 'array';

    protected $allowedFields = [
        'name',
        'location',
        'description',
        'schedules',
    ];
}

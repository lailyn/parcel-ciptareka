<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JadwalPickupModel extends Model
{
    use HasFactory;
    protected $table = 'jadwal_pickup';
    protected $primaryKey = 'id';
}

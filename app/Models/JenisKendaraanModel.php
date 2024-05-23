<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JenisKendaraanModel extends Model
{
    use HasFactory;
    protected $table = 'jenis_kendaraan';
    protected $primaryKey = 'id';
}

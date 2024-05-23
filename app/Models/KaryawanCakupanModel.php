<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KaryawanCakupanModel extends Model
{
    use HasFactory;
    protected $table = 'karyawan_cakupan';
    protected $primaryKey = 'id';    
}

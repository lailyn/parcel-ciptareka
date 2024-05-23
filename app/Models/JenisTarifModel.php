<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JenisTarifModel extends Model
{
    use HasFactory;
    protected $table = 'jenis_tarif';
    protected $primaryKey = 'id';
}

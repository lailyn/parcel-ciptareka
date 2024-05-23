<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TarifDetailModel extends Model
{
    use HasFactory;
    protected $table = 'tarif_detail';
    protected $primaryKey = 'id';
}

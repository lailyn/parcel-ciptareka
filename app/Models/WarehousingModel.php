<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WarehousingModel extends Model
{
    use HasFactory;
    protected $table = 'pickup';
    protected $primaryKey = 'id';
}

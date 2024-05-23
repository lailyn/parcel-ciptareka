<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PickupDetailModel extends Model
{
    use HasFactory;
    protected $table = 'pickup_detail';
    protected $primaryKey = 'id';    
}

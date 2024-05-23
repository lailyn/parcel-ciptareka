<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SettingModel extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $table = 'setting';
    protected $primaryKey = 'id';
    
}

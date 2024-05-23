<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StatusModel extends Model
{
    use HasFactory;
    protected $table = 'table_status';
    protected $primaryKey = 'id';
    protected $fillable = [
        'name',
        'file_path'
    ];
}

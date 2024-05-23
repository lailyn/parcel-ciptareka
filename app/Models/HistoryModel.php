<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HistoryModel extends Model
{
    use HasFactory;
    protected $table = 'manifestUtama_history';
    protected $primaryKey = 'id';
    protected $fillable = [
        'name',
        'file_path'
    ];
}

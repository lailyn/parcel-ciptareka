<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MemberModel extends Model
{
    use HasFactory;
    protected $table = 'member';
    protected $primaryKey = 'id';    
    protected $fillable = ['name','no_ktp','no_hp','alamat','kodepos','tgl_lahir'];
}

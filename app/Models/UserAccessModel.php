<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserAccessModel extends Model
{
    use HasFactory;
    protected $table = 'user_access';
    protected $primaryKey = 'id';
}

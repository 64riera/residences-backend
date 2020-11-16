<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TypeUser extends Model
{
    use HasFactory;
    protected $table = 'type_users';
    protected $primaryKey = 'id';
    public $timestamps = true;
}

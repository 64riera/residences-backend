<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdminArea extends Model
{
    use HasFactory;

    protected $table = 'admin_areas';
    protected $primaryKey = 'id';
    public $timestamps = true;
}

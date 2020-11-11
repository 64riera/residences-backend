<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActiveUserProcess extends Model
{
    use HasFactory;

    protected $table = 'active_user_processes';
    protected $primaryKey = 'id';
    public $timestamps = true;
}

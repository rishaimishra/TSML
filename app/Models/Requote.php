<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Requote extends Model
{
    protected $table = 'requotes';
    protected $fillable  = ['schedule_id','status'];
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Smremark extends Model
{
	use SoftDeletes;

    protected $table = 'sm_remarks';
    protected $fillable  = ['user_id','rfq_no','remarks','status'];

    protected $dates = ['deleted_at'];


}

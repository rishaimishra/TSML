<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class QuoteSchedule extends Model
{

	 use SoftDeletes;
	 
     protected $table = "quote_schedules";
     protected $fillable = ['quote_id','quantity','to_date','from_date','created_at','updated_at'];

     protected $dates = ['deleted_at'];
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Quote extends Model
{

	use SoftDeletes;
    
     protected $table = "quotes";

     protected $fillable = ['user_id','product_id','rfq_no','quantity','kam_price','expected_price','plant','location','kam_status','cus_status','created_at','updated_at'];

     protected $dates = ['deleted_at'];


    public function schedules()
    {
        return $this->hasMany('App\Models\QuoteSchedule','quote_id','id');
    }
}

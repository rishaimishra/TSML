<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Quote extends Model
{

	use SoftDeletes;
    
     protected $table = "quotes";

     protected $fillable = ['user_id','product_id','cat_id','rfq_no','quantity','quote_no','kam_status','cus_status','reject_reason','valid_till','created_at','updated_at'];

     protected $dates = ['deleted_at'];


    public function schedules()
    {
        return $this->hasMany('App\Models\QuoteSchedule','quote_id','id');
    }

    public function product()
    {
    	return $this->belongsTo('App\Models\Product','product_id','id');
    }

    public function subCategory()
    {
        return $this->hasOne('App\Models\ProductSubCategory','pro_id','id');
    }

    public function category()
    {
        return $this->belongsTo('App\Models\Category','cat_id','id');
    }
}

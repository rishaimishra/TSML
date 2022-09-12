<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $table = 'products';
    protected $guarded = [];

    
    // public function getSubCateDetails(){
    //     return $this->belongsTo('App\Models\ProductSubCategory','sub_cat_id','id');
    // }
}

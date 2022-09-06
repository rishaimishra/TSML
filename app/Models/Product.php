<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $table = 'products';
    protected $guarded = [];

    public function getCateDetails(){
        return $this->belongsTo('App\Models\Category','cat_id','id');
    }
    public function getSubCateDetails(){
        return $this->belongsTo('App\Models\ProductSubCategory','sub_cat_id','id');
    }
}

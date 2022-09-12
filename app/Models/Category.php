<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $table = 'categorys';
    protected $guarded = [];

    public function getProductDetails(){
        return $this->belongsTo('App\Models\Product','product_id','id');
    }
    public function subCategory()
    {
        return $this->hasMany('App\Models\ProductSubCategory','cat_id','id');
    }
}

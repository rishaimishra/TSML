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

    public function getSubCategoryDetails(){
        return $this->hasMany('App\Models\ProductSubCategory', 'id', 'cat_id');
    }
}

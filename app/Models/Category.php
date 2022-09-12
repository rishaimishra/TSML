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
<<<<<<< HEAD
    public function subCategory()
    {
        return $this->hasMany('App\Models\ProductSubCategory','cat_id','id');
=======

    public function getSubCategoryDetails(){
        return $this->hasMany('App\Models\ProductSubCategory', 'id', 'cat_id');
>>>>>>> e5846d8bb497bc23ad02f6d51def44104a025659
    }
}

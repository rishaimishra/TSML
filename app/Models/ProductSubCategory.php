<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductSubCategory extends Model
{
    protected $table = 'sub_categorys';
    protected $guarded = [];

    public function getCategoryDetails(){
        return $this->hasOne('App\Models\Category', 'id', 'cat_id');
    }
}

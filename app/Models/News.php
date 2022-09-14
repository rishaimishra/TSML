<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class News extends Model
{
    protected $table = "news";

     protected $fillable = ['user_id','title','description','image','created_at','updated_at'];
}

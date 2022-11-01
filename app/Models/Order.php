<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
     protected $table = "orders";

     protected $fillable = ['po_no','rfq_no','status','letterhead','po_date','created_at','updated_at'];
}

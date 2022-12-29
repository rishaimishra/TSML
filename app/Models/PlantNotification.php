<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PlantNotification extends Model
{
    protected $table = 'plant_notifications';
    protected $guarded = [];

    public function userName()
    {
        return $this->hasOne('App\User','id','sender_id');
    }
}



<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VehicleModel extends Model
{
    use HasFactory;

    public function manufacture(){

        return $this->belongsTo(Manufacture::class, 'manufacture_id');
    }
}

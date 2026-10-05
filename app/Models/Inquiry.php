<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Inquiry extends Model implements HasMedia
{
    use HasFactory;
    use InteractsWithMedia;

    protected $fillable = ['name','email', 'phone', 'address', 'payment_method', 'purchase_time', 'url', 'vehicle_id','type', 'status' ];
    protected $with = ['media'];
    protected $table = 'inquiries';
}

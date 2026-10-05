<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Vehicle extends Model implements HasMedia
{
    use HasFactory;
    use InteractsWithMedia;

    protected $appends = ['FeaturesList', 'FuelTypeString', 'TransmissionString', 'AvailabilityString'];

    protected $casts = [
        // 'features' => 'array',
    ];

    public function vehicleType()
    {
        return $this->belongsTo(Type::class);
    }

    public function manufacture()
    {
        return $this->belongsTo(Manufacture::class);
    }

    public function vehicleModel()
    {
        return $this->belongsTo(VehicleModel::class);
    }

    public function exColor()
    {
        return $this->belongsTo(Color::class, 'ex_color');
    }

    public function inColor()
    {
        return $this->belongsTo(Color::class, 'in_color');
    }

    // public function features()
    // {
    // dd($this->features);
    // return $this->belongsToMany(Feature::class, 'vehicles', 'id', 'features')->whereIn('id',json_decode( $this->features)??[]);
    // }

    public function getFeaturesListAttribute()
    {
        $features  = json_decode($this->features, true);
        // $features  = json_decode($features, true);
        return $features;
    }

    public function getFuelTypeStringAttribute($type)
    {
        $ft = '';
        if ($type == 0) {
            $ft = 'Diesel';
        } else if ($type == 1) {
            $ft = 'Petrol';
        } else if ($type == 2) {
            $ft = 'Hybrid';
        } else if ($type == 3) {
            $ft = 'Electric';
        }

        return $ft;
    }
    public function getTransmissionStringAttribute($value)
    {
        $t = '';
        if ($value == 0) {
            $t = 'Auto';
        } else if ($value == 1) {
            $t = 'Manual';
        } else if ($value == 2) {
            $t = 'Triptonic';
        }

        return $t;
    }
    public function getAvailabilityStringAttribute($value)
    {
        $t = '';
        if ($value == 0) {
            $t = 'Available';
        } else if ($value == 1) {
            $t = 'Arriving';
        } else if ($value == 2) {
            $t = 'Sold';
        }

        return $t;
    }
}

<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class VehicleResource extends JsonResource
{
   /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return[
            'id' =>$this->id,
            'vehicle_type' =>new TypeResource($this->vehicleType),
            'manufacture'=>new ManufactureResource($this->manufacture),
            'model'=>new ModelResource($this->manufacture),
            // 'seo_url'=>$this->seo_url,
            'ex_color' =>new ColorResource($this->exColor),
            'in_color' =>new ColorResource($this->inColor),
            'features'=>$this->features,
            'transmission'=>$this->TransmissionString,
            'year' =>$this->year,
            'chassis_no' =>$this->chassis_no,
            'condition'=>$this->condition,
            'seats'=>$this->seats,
            'doors' =>$this->doors,
            'passengers' =>$this->passengers,
            'engine_capacity'=>$this->engine_capacity,
            'mileage'=>$this->mileage,
            'fuel_type' =>$this->FuelTypeString,
            'drive_type' =>$this->drive_type,
            'interior_condition'=>$this->interior_condition,
            'availability'=>$this->AvailabilityString,
            'auction_grade'=>$this->auction_grade,
            'editorContent' =>$this->editorContent,
            'featured' =>$this->featured,
            'latest'=>$this->latest,
            'status'=>$this->status,
            'media' => count($this->media) > 0 ?new MediaResource($this->media()->first()) :null
        ];
    }
}
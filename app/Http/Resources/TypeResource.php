<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TypeResource extends JsonResource
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
            'title' =>$this->title,
            'status'=>$this->status,
            'featured'=>$this->featured,
            'media' => count($this->media) > 0 ?new MediaResource($this->media()->first()) :null
        ];
    }
}
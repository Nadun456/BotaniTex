<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CustomerResource extends JsonResource
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
            'name' =>$this->name,
            'address'=>$this->address,
            'email'=>$this->email,
            'email_verified_at' =>$this->email_verified_at,
            'phone' =>$this->phone,
            'phone_verified_at'=>$this->phone_verified_at,
            'password'=>$this->password,
            'avatar' =>$this->avatar,
            'provider' =>$this->provider,
            'provider_id'=>$this->provider_id,
            'access_token'=>$this->access_token,
            'status'=>$this->status,
        ];
    }
}
<?php

namespace App\Http\Resources\Gig;

use Illuminate\Http\Resources\Json\JsonResource;

class GigPackageResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'price' => $this->price,
            'time' => $this->time
        ];
    }
}

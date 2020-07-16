<?php

namespace App\Http\Resources\JobRequest;

use App\Http\Resources\Gig\GigCategoryResource;
use Illuminate\Http\Resources\Json\JsonResource;

class JobRequestResource extends JsonResource
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
            'description' => $this->description,
            'file_url' => $this->file_url(),
            'time' => $this->time,
            'price' => $this->price,
            'price_type' => $this->price_type,
            'location' => $this->buyer_location,
            'request_type' => $this->request_type,
            'status' => $this->status,
            'time' => $this->time,
            'category' => [
                'id' => $this->category->id,
                'title' => $this->category->title
            ],
            'subcategory' => [
                'id' => $this->subcategory->id,
                'title' => $this->subcategory->title
            ],
            'service_type' => [
                'id' => $this->service_type->id,
                'title' => $this->service_type->title
            ],
            'offers_count' => count($this->offers->toArray()),
            'offers' => JobOffersResource::collection($this->offers)
        ];
    }
}

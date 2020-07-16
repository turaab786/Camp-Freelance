<?php

namespace App\Http\Resources\Gig;

use Illuminate\Http\Resources\Json\JsonResource;

class UserGigResource extends JsonResource
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
            'gig_type' => $this->gig_type,
            'hourly_rate' => $this->hourly_rate,
            'category' => [
                'id' => $this->category->id,
                'title' => $this->category->title
            ],
            'subcategory' => [
                'id' => $this->subcategory->id,
                'title' => $this->subcategory->title
            ],
            'servicetype' => [
                'id' => $this->servicetype->id,
                'title' => $this->servicetype->title
            ],
            'gallery' => GigGalleryResource::collection($this->gallery),
            // 'gallery' => $this->gallery,
            'packages' => GigPackageResource::collection($this->packages),
            'requirements' => GigRequirementResource::collection($this->requirements)
        ];
    }
}

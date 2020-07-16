<?php

namespace App\Http\Resources\JobOffer;

use Illuminate\Http\Resources\Json\JsonResource;

class JobRequestOffersResource extends JsonResource
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
            'price' => $this->price,
            'time' => $this->time,
            'no_of_revisions' => $this->no_of_revisions,
            'ask_for_gig_requirements' => !!$this->ask_for_gig_requirements,
            'seller' => [
                'id' => $this->seller->id,
                'name' => $this->seller->name,
                'profile_image' => $this->seller ? $this->seller->getProfileImg() : null
            ],
            'gig' => [
                'id' => $this->gig->id,
                'title' => $this->gig->title,
                'file_url' => $this->gig ? $this->gig->gallery[0]->file_url() : null
            ]
        ];
    }
}

<?php

namespace App\Http\Resources\JobOffer;

use Illuminate\Http\Resources\Json\JsonResource;

class JobOfferResource extends JsonResource
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
            'gig' => [
                'id' => $this->gig->id,
                'title' => $this->gig->title,
                'file_url' => $this->gig ? $this->gig->gallery[0]->file_url() : null
            ],
            'buyer' => [
                'id' => $this->buyer->id,
                'name' => $this->buyer->name,
                'profile_image' => $this->buyer ? $this->buyer->getProfileImg() : null
            ],
            'jobrequest' => [
                'id' => $this->jobrequest->id,
                'description' => $this->jobrequest->description,
                'file_url' => $this->jobrequest ? $this->jobrequest->file_url() : null,
                'time' => $this->jobrequest->time,
                'price' => $this->jobrequest->price,
                'is_hourly' => !!$this->jobrequest->is_hourly,
            ]
        ];
    }
}

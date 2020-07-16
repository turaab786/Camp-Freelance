<?php

namespace App\Http\Resources\JobRequest;

use Illuminate\Http\Resources\Json\JsonResource;

class JobOffersResource extends JsonResource
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
            'ask_for_gig_requirements' => !!$this->ask_for_gig_requirements
        ];
    }
}

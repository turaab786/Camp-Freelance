<?php

namespace App\Http\Resources\Chat;

use Illuminate\Http\Resources\Json\JsonResource;

class OtherUserDataResource extends JsonResource
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
            'name' => $this->name,
            'email' => $this->email,
            'phone_number' => $this->phone_number,
            'country_code' => $this->country_code,
            'country_code_text' => $this->country_code_text,
            'profile_image' => $this->getProfileImg(),
            'member_since' => date('F j, Y', strtotime($this->created_at)),
            'profile_publicly_visible' => $this->profile_publicly_visible,
            'account' => [
                'accept_custom_offers' => !!$this->account->accept_custom_offers
            ],
            'details' => [
                'user_intro' => $this->details->user_intro ? $this->details->user_intro : null,
                'user_description' => $this->details->user_description ? $this->details->user_description : null,
                'user_average_response_time' => $this->details->user_average_response_time ? $this->details->user_average_response_time : null,
                'location' => $this->details->location ? $this->details->location : null
            ]
        ];
    }
}

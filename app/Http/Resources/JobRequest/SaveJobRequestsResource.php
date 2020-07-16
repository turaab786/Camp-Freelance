<?php

namespace App\Http\Resources\JobRequest;

use Illuminate\Http\Resources\Json\JsonResource;

class SaveJobRequestsResource extends JsonResource
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
            'requestdetails' => [
                'id' => $this->requestdetails->id,
                'description' => $this->requestdetails->description,
                'file_url' => $this->requestdetails->file_url(),
                'time' => $this->requestdetails->time,
                'price' => $this->requestdetails->price,
                'price_type' => $this->requestdetails->id,
                'buyer_location' => $this->requestdetails->buyer_location,
                'request_type' => $this->requestdetails->request_type,
                'status' => $this->requestdetails->status,
                'category' => [
                    'id' => $this->requestdetails->category->id,
                    'title' => $this->requestdetails->category->title
                ],
                'subcategory' => [
                    'id' => $this->requestdetails->subcategory->id,
                    'title' => $this->requestdetails->subcategory->title
                ],
                'service_type' => [
                    'id' => $this->requestdetails->service_type->id,
                    'title' => $this->requestdetails->service_type->title
                ],
                'offers_count' => count($this->requestdetails->offers)
            ],
            'buyerdetails' => [
                'id' => $this->buyerdetails->id,
                'name' => $this->buyerdetails->name,
                'email' => $this->buyerdetails->email,
                'phone_number' => $this->buyerdetails->phone_number,
                'profile_img' => $this->buyerdetails->getProfileImg(),
                'city' => $this->buyerdetails->details->city ? $this->buyerdetails->details->city : 'Not Available',
                'country' => $this->buyerdetails->details->country ? $this->buyerdetails->details->country : 'Not Available'
            ]
        ];
    }
}

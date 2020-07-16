<?php

namespace App\Http\Resources\User;

use App\Http\Resources\Shared\MembershipPlanResource;
use Illuminate\Http\Resources\Json\JsonResource;

class UserProfileResource extends JsonResource
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
            'profile_img' => $this->profile_img,
            'user_role' => $this->role,
            'member_since' => "Member Since " . date('l F j, Y', strtotime($this->created_at)),
            'is_buyer' => !!$this->is_buyer,
            'is_2fa_verified' => !!$this->is_2fa_verified,
            'is_2fa_enabled' => !!$this->is_2fa_enabled,
            'token' => $this->getTokken()
        ];
    }
}

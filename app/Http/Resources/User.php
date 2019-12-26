<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class User extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return parent::toArray($request) + [
            'should_confirm_password' => $this->shouldConfirmPassword(),
            'on_trial' => $this->onGenericTrial(),
            'subscription' => new Subscription($this->subscription()),
        ];
    }
}

<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserMinlistResource extends JsonResource
{
    public function toArray($request)
    {
        $imageUrl = @asset('images/users/avatar-4.jpg');

        return [
            'id' => $this->id,
            'name' => $this->name,
            'image' => $imageUrl,
            'email' => $this->email,
        ];
    }

}

<?php

namespace App\Http\Resources;

use App\Services\FileService;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public function toArray($request)
    {
        $fileService = resolve(FileService::class);

        $imageUrl = @asset('images/users/avatar-4.jpg');

        return [
            'id' => $this->id,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'keyword' => $this->keyword,
            'name' => $this->name,
            'address' => $this->address ?? false,
            'image' => $imageUrl,
            'avatar' => $fileService->getResource($this->avatar),
            'role' => $this->role,
            'phone' => $this->phone,
            'email' => $this->email,
            'created_at' => strtotime($this->created_at),
        ];
    }

}

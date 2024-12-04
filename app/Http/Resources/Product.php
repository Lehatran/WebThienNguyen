<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class Product extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'price' => $this->price,
            'status' => $this->status,
            'id_category' => $this->id_category,
            'create_day' => $this->create_day,
            'id_address' => $this->id_address,
            'id_user' => $this->id_user,
            'img' => $this->img,
            'isExist' => $this->isExist,
        ];
    }
}

<?php

namespace App\Http\Resources;

use App\Http\Resources\Profile\BusinessProfileResource;
use App\Http\Resources\Profile\UserProfileResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MagazineResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'=>$this->id,
            'title' => $this->title,
            'description' => $this->description,
            'file_demo' => asset('magazine/' . $this->file_demo),
            "auther"=>$this->auther,
            'created_at' =>$this->created_at,

        ];
    }
}

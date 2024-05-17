<?php

namespace App\Http\Resources;

use App\Http\Resources\Profile\BusinessProfileResource;
use App\Http\Resources\Profile\UserProfileResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class projectResource extends JsonResource
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
            'video_demo' =>asset('videos/'.$this->video_demo),//$this->video_demo, //
            'status' =>$this->status,
            'coordinator_id' =>$this->coordinator_id,
            'StarRating' =>$this->StarRating,
            'department_id' =>$this->department_id,
            'Year' =>$this->Year
        ];
    }
}

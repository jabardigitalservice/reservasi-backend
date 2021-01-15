<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ReservationResource extends JsonResource
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
            'title' => $this->title,
            'description' => $this->description,
            'user_fullname' => $this->user_fullname,
            'asset_name' => $this->asset_name,
            'asset_description' => $this->asset_description,
            'date' => $this->date,
            'start_time' => $this->start_time,
            'end_time' => $this->end_time,
            'approval_status' => $this->approval_status,
            'approval_date' => $this->approval_date,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'note' => $this->note,
        ];
    }
}

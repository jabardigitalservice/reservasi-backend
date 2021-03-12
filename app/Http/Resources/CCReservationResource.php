<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CCReservationResource extends JsonResource
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
            'user_id_reservation' => $this->user_id_resercation,
            'name' => $this->name,
            'reservation_code' => $this->reservation_code,
            'nik' => $this->nik,
            'organization' => $this->organization,
            'organization_address' => $this->organization_address,
            'phone_number' => $this->phone_number,
            'email' => $this->email,
            'purpose' => $this->purpose,
            'participant' => $this->participant,
            'reservation_date' => $this->reservation_date,
            'shift' => $this->shift,
            'approval_status' => $this->approval_status,
            'note' => $this->note,
            'approval_date' => $this->approval_date,
            'user_id_updated' => $this->user_id_updated,
            'user_id_deleted' => $this->user_id_deleted,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ];
    }
}

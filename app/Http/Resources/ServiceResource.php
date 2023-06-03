<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ServiceResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'customer_name' => $this->customer_name,
            'origin_coordinate' => [$this->origin_coordinate->latitude, $this->origin_coordinate->longitude],
            'destination_coordinate' => [$this->destination_coordinate->latitude, $this->destination_coordinate->longitude],
            'status' => $this->status,
            'updated_at' => $this->updated_at->format('Y-m-d h:i:s')
        ];
    }
}

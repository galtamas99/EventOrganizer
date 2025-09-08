<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EventResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'          => $this->id,
            'title'       => $this->title,
            'description' => $this->description,
            'starts_at'   => $this->starts_at->format('Y-m-d\TH:i'),
            'location'    => $this->location,
            'capacity'    => $this->capacity,
            'ticket_price'=> $this->ticket_price,
            'category'    => $this->category,
            'status'      => $this->status,
            'created_at'  => $this->created_at->format('Y-m-d\TH:i'),
            'updated_at'  => $this->updated_at->format('Y-m-d\TH:i'),
        ];
    }
}

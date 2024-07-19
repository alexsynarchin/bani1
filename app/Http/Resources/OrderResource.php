<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;
class OrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'created_at' => $this->created_at->format('d-m-Y'),
            'price' => $this->price,
            'date' => Carbon::parse($this -> date) -> format('d-m-y'),
            'start' => Carbon::parse($this -> start) -> format('H:i'),
            'end' => Carbon::parse($this -> end) -> format('H:i'),
            //'client' => $this->client,
            'status' => $this->status
        ];
    }
}

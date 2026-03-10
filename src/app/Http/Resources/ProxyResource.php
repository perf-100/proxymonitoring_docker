<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProxyResource extends JsonResource
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
            'type' => $this->type,
            'raw' => $this->raw,
            'status' => $this->status,
            'checked_at' => $this->checked_at,
            'check_interval' => $this->check_interval,
            'comment' => $this->comment,
        ];
    }
}

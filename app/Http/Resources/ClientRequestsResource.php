<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ClientRequestsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "id"         => $this->id,
            "name"       => $this->name,
            "email"      => $this->email,
            "status"     => $this->status,
            "message"    => $this->message,
            "comment"    => $this->comment,
            "createdAt" => $this->created_at,
            "updatedAt" => $this->updated_at,
            "user"       => new UserResource($this->user),
        ];
    }
}

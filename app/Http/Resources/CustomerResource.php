<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CustomerResource extends JsonResource
{
    public static $wrap = NULL;

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'                => $this->id,
            'name'              => $this->name,
            'customer_groups'   => $this->whenLoaded('customerGroups'),
            'created_at'        => $this->created_at,
            'updated_at'        => $this->updated_at,
        ];
    }
}

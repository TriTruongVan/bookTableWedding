<?php

namespace App\Http\Resources;

use App\Enums\UserRoles;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class CustomerResource extends JsonResource
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
            'name' => $this->name,
            'street' => $this->street,
            'address' => $this->address,
            'ward' => $this->ward,
            'tel' => $this->tel,
            'role' => $this->role,
            'canAccess' => Auth::user()->role === UserRoles::Admin
        ];
    }
}

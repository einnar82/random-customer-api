<?php

namespace App\Modules\Customer\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Modules\Customer\Entities\Customer */
class CustomerResource extends JsonResource
{
    /**
     * @param Request $request
     * @return array
     */
    public function toArray($request): array
    {
        return [
            'id' => (int) $this->getId(),
            'full_name' => $this->getFullName(),
            'email' => $this->getEmail(),
            'country' => $this->getCountry()
        ];
    }
}

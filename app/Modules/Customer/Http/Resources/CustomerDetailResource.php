<?php

namespace App\Modules\Customer\Http\Resources;

use Illuminate\Http\Request;

/** @mixin \App\Modules\Customer\Entities\Customer */
class CustomerDetailResource extends CustomerResource
{
    /**
     * @param Request $request
     * @return array
     */
    public function toArray($request): array
    {
        return array_merge(parent::toArray($request), [
            'username' => $this->getUsername(),
            'gender' => $this->getGender(),
            'city' => $this->getCity(),
            'phone' => $this->getPhone()
        ]);
    }
}

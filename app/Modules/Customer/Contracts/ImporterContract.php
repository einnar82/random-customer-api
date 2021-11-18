<?php

namespace App\Modules\Customer\Contracts;

use Illuminate\Support\Collection;

interface ImporterContract
{
    /**
     * @param array $options
     * @return Collection
     */
    public function results(array $options = []) : Collection;
}

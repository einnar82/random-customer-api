<?php

namespace App\Modules\Customer\Contracts;

use App\Modules\Customer\Entities\Customer;

interface CustomerToImportContract
{
    /**
     * @param array|mixed $row
     * @param Customer|null $customer
     * @return Customer
     */
    public function toImport(array $row, ?Customer $customer = null) : Customer;
}

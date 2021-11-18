<?php

declare(strict_types=1);

namespace Stubs;

use App\Modules\Customer\Contracts\CustomerToImportContract;
use App\Modules\Customer\Entities\Customer;
use Illuminate\Support\Arr;

class CustomerImporterClassStub implements CustomerToImportContract
{
    /**
     * @var string
     */
    private const MALE = 'male';

    /**
     * @param array|mixed $row
     * @param Customer|null $customer
     * @return Customer
     */
    public function toImport(array $row, ?Customer $customer = null): Customer
    {
        $customer = ($customer ?? new Customer())
            ->setEmail(Arr::get($row, 'email'))
            ->setFirstName(Arr::get($row, 'name.first'))
            ->setLastName(Arr::get($row, 'name.last'))
            ->setUsername(Arr::get($row, 'login.username'))
            ->setGender(Arr::get($row, 'gender') === self::MALE ? 0 : 1)
            ->setCountry(Arr::get($row, 'location.country'))
            ->setCity(Arr::get($row, 'location.city'))
            ->setPhone(Arr::get($row, 'phone'))
            ->setPassword(Arr::get($row, 'login.md5'));

        return $customer;
    }
}

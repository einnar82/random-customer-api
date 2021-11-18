<?php

namespace App\Modules\Customer\Contracts;

use App\Modules\Customer\Entities\Customer;
use Illuminate\Pagination\LengthAwarePaginator;
use Doctrine\Common\Collections\Criteria;

interface CustomerRepositoryContract
{
    /**
     * @param string $order
     * @param int $limit
     * @param int $page
     * @return LengthAwarePaginator
     */
    public function all(string $order = Criteria::DESC, int $limit = 15, int $page = 1): LengthAwarePaginator;

    /**
     * @param int $id
     * @return Customer|null
     */
    public function findCustomer(int $id): ?Customer;

    /**
     * @param object $entity
     * @return void
     */
    public function saveEntity($entity): void;


    /**
     * Flush customer entity
     * @return void
     */

    public function flush(): void;
}

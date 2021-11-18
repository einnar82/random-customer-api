<?php

namespace Customer;

use App\Modules\Customer\Contracts\CustomerRepositoryContract;
use App\Modules\Customer\Entities\Customer;
use App\Modules\Customer\Repositories\CustomerRepository;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\EntityManagerInterface;
use RefreshDoctrineDatabase;
use TestCase;

/**
 * @covers  \App\Modules\Customer\Repositories\CustomerRepository;
 */
class CustomerRepositoryTest extends TestCase
{
    use RefreshDoctrineDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->createDoctrineDatabase();
    }

    protected function tearDown(): void
    {
        $this->dropDoctrineDatabase();
    }

    public function testIfReturnSameCount(): void
    {
        entity(Customer::class, 15)->create();
        $repository = $this->app->make(CustomerRepositoryContract::class);
        $this->assertCount(15, $repository->all());
    }

    public function testIfAscendingOrderIsCorrect(): void
    {
        $email = 'bar@email.com';
        entity(Customer::class)->create([
            'email' => $email
        ]);
        $repository = $this->app->make(CustomerRepositoryContract::class);
        $customer = $repository->all(Criteria::ASC);

        // $this->
        $this->assertCount(1, $customer);
    }
}

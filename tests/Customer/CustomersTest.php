<?php

namespace Customer;

use App\Modules\Customer\Entities\Customer;
use TestCase;
use Illuminate\Http\Response;
use Illuminate\Support\Collection;
use RefreshDoctrineDatabase;

class CustomersTest extends TestCase
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

    public function testIfCanGetCustomer(): void
    {
        entity(Customer::class, 1)->create();
        $this->get('customers/1');
        $this->assertResponseOk();
        $this->seeJsonStructure([
            'data' => [
                'id',
                'full_name',
                'email',
                'country'
            ],
        ]);
    }


    public function testIfCanPaginate(): void
    {
        $this->get('customers/?page=2');
        $this->assertResponseOk();
        $this->seeJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'full_name',
                    'email',
                    'country',
                ],
            ],
        ]);
    }


    public function testIfCanQueryResults(): void
    {
        entity(Customer::class, 10)->create();
        $this->get('customers/?order=ASC');
        $data = Collection::make($this->response->json('data'));
        $ids = $data->pluck('id');
        $this->assertSame([1, 2, 3, 4, 5, 6, 7, 8, 9, 10], $ids->toArray());
        $this->assertResponseOk();
        $this->seeJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'full_name',
                    'email',
                    'country',
                ],
            ],
        ]);

        $this->get('customers/?order=DESC');
        $data = Collection::make($this->response->json('data'));
        $ids = $data->pluck('id');
        $this->assertSame([10, 9, 8, 7, 6, 5, 4, 3, 2, 1], $ids->toArray());
        $this->assertResponseOk();
        $this->seeJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'full_name',
                    'email',
                    'country',
                ],
            ],
        ]);
    }

    public function testIfCustomerNotFound(): void
    {
        $this->get('customers/100000');
        $this->assertResponseStatus(Response::HTTP_NOT_FOUND);

        $this->get('customers/customer-id');
        $this->assertResponseStatus(Response::HTTP_NOT_FOUND);
    }


    public function testIfCanGetAllCustomers(): void
    {
        entity(Customer::class, 10)->create();
        $this->get('customers');
        $this->assertResponseOk();
        $data = Collection::make($this->response->json('data'));
        $ids = $data->pluck('id');
        $arrayOfIds = $ids->toArray();
        $this->assertSame([10, 9, 8, 7, 6, 5, 4, 3, 2, 1], $arrayOfIds);
        $this->assertCount(10, $data->all());
        $this->assertResponseOk();
        $this->seeJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'full_name',
                    'email',
                    'country',
                ],
            ],
        ]);
    }
}

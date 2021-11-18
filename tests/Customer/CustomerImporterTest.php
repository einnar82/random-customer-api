<?php

namespace Customer;

use App\Modules\Customer\Contracts\CustomerManagerContract;
use App\Modules\Customer\Contracts\CustomerRepositoryContract;
use App\Modules\Customer\Entities\Customer;
use App\Modules\Customer\Imports\CustomerImporter;
use App\Modules\Customer\Models\CustomerImportModel;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\ToolsException;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Support\Collection;
use Mockery;
use RefreshDoctrineDatabase;
use Stubs\CustomerImporterClassStub;
use TestCase;

/**
 * @covers \App\Modules\Customer\Imports\CustomerImporter;
 */
class CustomerImporterTest extends TestCase
{
    use RefreshDoctrineDatabase;

    public function testIfJsonDriverClassIsWorking(): void
    {
        entity(Customer::class, 10)->make();
        entity(Customer::class)->create([
            'email' => 'email@example.com',
            'firstName' => 'Jefferson',
            'lastName' => 'Thompson'
        ]);

        /** @var CustomerManagerContract @manager */
        $manager = Mockery::mock(CustomerManagerContract::class);
        $manager->shouldReceive('results')
            ->andReturn(new Collection([
                [
                    'email' => 'email@example.com',
                    'name' => [
                        'first' => 'Jefferson',
                        'last' => 'Johnson'
                    ],
                    'location' => [
                        'country' => 'Philippines',
                        'city' => 'Bacoor'
                    ],
                    'login' => [
                        'username' => 'testUsername',
                        'md5' => md5('password')
                    ],
                    'phone' => '(02) 222-2222'
                ]
            ]));

        /** @var Dispatcher @dispatcher */
        $dispatcher = Mockery::mock(Dispatcher::class);
        $dispatcher->shouldReceive('dispatch')->andReturnNull();

        $importer = new CustomerImporter(
            $manager,
            $this->app->make(CustomerRepositoryContract::class),
            $dispatcher
        );

        $importer->import(new CustomerImporterClassStub());

        $this->seeInDatabase('customers', [
            'email' => 'email@example.com',
            'first_name' => 'Jefferson',
            'last_name' => 'Johnson',
        ]);
    }

    public function testIfCanCreateCustomerFromEntity(): void
    {
        $entities = entity(Customer::class, 10)->make();
        $this->assertCount(10, $entities);
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->createDoctrineDatabase();
    }

    protected function tearDown(): void
    {
        $this->dropDoctrineDatabase();
    }
}

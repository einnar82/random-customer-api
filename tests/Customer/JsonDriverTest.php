<?php

namespace Customer;

use App\Modules\Customer\Drivers\JsonDriver;
use Faker\Factory as FactoryFaker;
use Helpers\JsonGeneratorHelper;
use Illuminate\Http\Client\Factory;
use TestCase;

/**
 * @covers \App\Modules\Customer\Drivers\JsonDriver;
 */
class JsonDriverTest extends TestCase
{
    protected Factory $factory;

    protected JsonGeneratorHelper $jsonGenerator;


    protected function setUp(): void
    {
        parent::setUp();
        $this->factory = new Factory();
        $this->jsonGenerator = new JsonGeneratorHelper();
    }

    /** @test */
    public function testIfJsonHasCorrectCount(): void
    {
        $count = 100;
        $http = $this->factory->fake([
            '*' => [
                'results' => $this->jsonGenerator->generateJsonResults(FactoryFaker::create(), $count)
            ]
        ]);
        $client = new JsonDriver(
            $http->baseUrl('/'),
            config('customer.json')
        );

        $this->assertCount($count, $client->results());
    }
}

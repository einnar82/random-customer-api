<?php

namespace Customer;

use App\Modules\Customer\Manager\ImporterManager;
use Illuminate\Http\Client\Factory;
use InvalidArgumentException;
use RefreshDoctrineDatabase;
use TestCase;

/**
 * @covers \App\Modules\Customer\Manager\ImporterManager;
 */
class ImporterManagerTest extends TestCase
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

    public function testIfCustomDriverNotFound(): void
    {
        $this->expectException(InvalidArgumentException::class);

        $manager = new ImporterManager(
            $this->app,
            $this->app[Factory::class]->fake()
        );

        $manager->driver('xml');
    }

    public function testIfCanSetCustomDriverAsDefault(): void
    {
        $manager = new ImporterManager(
            $this->app,
            $this->app[Factory::class]->fake()
        );

        $manager->extend(__CLASS__, function () {
            return $this;
        });
        $manager->setDefaultDriver(__CLASS__);
        $this->assertSame(__CLASS__, $manager->getDefaultDriver());
    }

    public function testGetDrivers()
    {
        $manager = new ImporterManager(
            $this->app,
            $this->app[Factory::class]->fake()
        );

        $manager->extend(__CLASS__, function () {
            return $this;
        });
        $manager->driver(__CLASS__);
        $this->assertArrayHasKey(__CLASS__, $manager->getDrivers());
    }
}

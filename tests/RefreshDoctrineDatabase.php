<?php


use App\Modules\Customer\Entities\Customer;
use Doctrine\ORM\Tools\ToolsException;

trait RefreshDoctrineDatabase
{
    public function createDoctrineDatabase(): void
    {
        $this->artisan('doctrine:schema:create');
        // entity(Customer::class, 10)->create();
    }

    public function dropDoctrineDatabase(): void
    {
        $this->artisan('doctrine:schema:drop', [
            '--force' => true,
        ]);
    }
}

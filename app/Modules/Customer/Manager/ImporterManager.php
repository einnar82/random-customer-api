<?php

namespace App\Modules\Customer\Manager;

use App\Modules\Customer\Contracts\CustomerManagerContract;
use App\Modules\Customer\Contracts\ImporterContract;
use App\Modules\Customer\Drivers\JsonDriver;
use Illuminate\Support\Manager;
use Illuminate\Contracts\Container\Container;
use Illuminate\Http\Client\Factory;
use Illuminate\Support\Collection;

class ImporterManager extends Manager implements CustomerManagerContract, ImporterContract
{
    protected Factory $factory;
    /**
     * Create a new manager instance.
     *
     * @param  \Illuminate\Contracts\Container\Container  $container
     * @return void
     */
    public function __construct(Container $container, ?Factory $factory = null)
    {
        parent::__construct($container);
        $this->factory = $factory ?? $this->container->make(Factory::class);
    }

    /**
     * @return string
     */
    public function getDefaultDriver(): string
    {
        return $this->config->get('customer.driver');
    }


    public function createJsonDriver(): JsonDriver
    {
        return new JsonDriver(
            $this->factory->baseUrl($this->config->get('customer.json.url'))->asForm(),
            $this->config->get('customer')[$this->getDefaultDriver()] ?? []
        );
    }


    /**
     * @param array $options
     * @return Collection
     */
    public function results(array $options = []): Collection
    {
        return $this->driver()->results($options);
    }


    /**
     * Set the default cache driver name.
     *
     * @param  string  $name
     * @return void
     */
    public function setDefaultDriver($name): void
    {
        $this->config->set('customer.driver', $name);
    }
}

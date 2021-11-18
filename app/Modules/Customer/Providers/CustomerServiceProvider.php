<?php

namespace App\Modules\Customer\Providers;

use Illuminate\Http\Client\Factory;
use App\Modules\Customer\Commands\ImportCustomerCommand;
use App\Modules\Customer\Contracts\CustomerImporterContract;
use App\Modules\Customer\Contracts\CustomerManagerContract;
use App\Modules\Customer\Contracts\CustomerRepositoryContract;
use App\Modules\Customer\Entities\Customer;
use App\Modules\Customer\Imports\CustomerImporter;
use App\Modules\Customer\Manager\ImporterManager;
use App\Modules\Customer\Repositories\CustomerRepository;
use Doctrine\ORM\EntityManagerInterface;
use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider;
use Illuminate\Contracts\Events\Dispatcher;

class CustomerServiceProvider extends ServiceProvider implements DeferrableProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->configure('customer');

        $this->app->bind(CustomerManagerContract::class, function ($app) {
            return new ImporterManager($app, $app->make(Factory::class));
        });

        $this->app->bind(CustomerRepositoryContract::class, function ($app) {
            // This is what Doctrine's EntityRepository needs in its constructor.
            return new CustomerRepository(
                $app->make(EntityManagerInterface::class),
                $app->make(EntityManagerInterface::class)->getClassMetaData(Customer::class)
            );
        });

        $this->app->bind(CustomerImporterContract::class, function ($app) {
            return new CustomerImporter(
                $app->make(CustomerManagerContract::class),
                $app->make(CustomerRepositoryContract::class),
                $app->make(Dispatcher::class)
            );
        });

        $this->app->singleton('customers.driver', function ($app) {
            return $app['customers']->driver();
        });

        $this->mapCustomerRoutes();
        $this->mergeConfigFrom(
            __DIR__ . '/../Config/customer.php',
            'customer'
        );
    }

    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot()
    {
        // Publishing is only necessary when using the CLI.
        $this->bootForConsole();
        // publishes config files
        $this->publishes([
            __DIR__ . '/../Config/customer.php' => base_path('config/customer.php'),
        ], 'config');
    }

    /**
     * Console-specific booting.
     *
     * @return void
     */
    protected function bootForConsole(): void
    {
        // Registering package commands.
        if ($this->app->runningInConsole()) {
            $this->commands([
                ImportCustomerCommand::class
            ]);
        }
    }


    /**
     * Register customer routes
     */

    protected function mapCustomerRoutes(): void
    {
        $this->app->router->group([
            'namespace' => 'App\Modules\Customer\Http\Controllers',
        ], function ($router) {
            require __DIR__ . '/../Routes/api.php';
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides(): array
    {
        return [CustomerManagerContract::class, 'customers.driver'];
    }
}

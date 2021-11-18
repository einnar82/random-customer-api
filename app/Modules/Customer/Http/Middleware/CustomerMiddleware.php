<?php

namespace App\Modules\Customer\Http\Middleware;

use App\Modules\Customer\Contracts\CustomerRepositoryContract;
use App\Modules\Customer\Entities\Customer;
use Closure;
use Illuminate\Support\Arr;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class CustomerMiddleware
{
    protected CustomerRepositoryContract $entityManager;

    /**
     * CustomerMiddleware constructor.
     * @param CustomerRepositoryContract $entityManager
     */
    public function __construct(CustomerRepositoryContract $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @param $request
     * @param Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $customerRoute = $request->route('customer');
        if ($customerRoute !== null) {
            $customer = $this->findCustomer((int) $customerRoute);
            $resolver = $request->getRouteResolver();
            $request->setRouteResolver(function () use ($customer, $resolver) {
                $route = $resolver();
                Arr::set($route[2], 'customer', $customer);

                return $route;
            });
        }

        return $next($request);
    }

    /**
     * @param int $id
     * @return Customer|null
     */
    protected function findCustomer(int $id) : ?Customer
    {
        /** @var Customer|null $find */
        return $this->entityManager->findCustomer($id);
    }
}

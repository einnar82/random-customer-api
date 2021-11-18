<?php

namespace App\Modules\Customer\Http\Controllers;

use App\Modules\Customer\Entities\Customer;
use App\Modules\Customer\Http\Resources\CustomerDetailResource;
use App\Modules\Customer\Http\Resources\CustomerResource;
use App\Modules\Customer\Repositories\CustomerRepository;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\EntityManagerInterface;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Resources\Json\JsonResource;
use Laravel\Lumen\Routing\Controller;

class CustomersController extends Controller
{
    /**
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @return AnonymousResourceCollection
     * @throws ValidationException
     */
    public function index(Request $request, EntityManagerInterface $entityManager): ?Responsable
    {
        /** @var CustomerRepository $repository */
        $repository = $entityManager->getRepository(Customer::class);

        return CustomerResource::collection(
            $repository->all(
                $order = $request->get('order', Criteria::DESC),
                $limit = (int) $request->get('limit', CustomerRepository::LIMIT),
                (int) $request->get('page', 1)
            )
                ->withPath(route('customer.index'))
                ->appends(compact('limit', 'order'))
        );
    }

    /**
     * @param Customer $customer
     * @return JsonResource
     */
    public function show(Customer $customer): Responsable
    {
        return new CustomerDetailResource($customer);
    }
}

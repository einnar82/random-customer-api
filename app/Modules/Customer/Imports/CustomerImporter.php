<?php

namespace App\Modules\Customer\Imports;

use App\Modules\Customer\Contracts\CustomerImporterContract;
use App\Modules\Customer\Contracts\CustomerManagerContract;
use App\Modules\Customer\Contracts\CustomerRepositoryContract;
use App\Modules\Customer\Contracts\CustomerToImportContract;
use App\Modules\Customer\Entities\Customer;
use App\Modules\Customer\Events\CustomerImportEvent;
use Illuminate\Contracts\Events\Dispatcher;

class CustomerImporter implements CustomerImporterContract
{
    protected CustomerManagerContract $manager;

    protected ?Dispatcher $dispatcher;

    protected CustomerRepositoryContract $customerRepository;

    /**
     * CustomerImporter constructor.
     * @param CustomerManagerContract $manager
     * @param CustomerRepositoryContract $customerRepository
     * @param ?Dispatcher $dispatcher
     */
    public function __construct(CustomerManagerContract $manager, CustomerRepositoryContract $customerRepository, ?Dispatcher $dispatcher = null)
    {
        $this->manager = $manager;
        $this->customerRepository = $customerRepository;
        $this->dispatcher = $dispatcher;
    }

    /**
     * @param CustomerToImportContract|string $contract
     * @param mixed[] $options
     */
    public function import($contract, array $options = []): void
    {
        $results = $this->manager->results($options);
        $results->each(function ($result, $index) use ($contract) {
            $this->customerRepository->saveEntity(
                $this->customerCreateOrUpdate(
                    $result,
                    is_string($contract) ? new $contract : $contract
                )
            );
            $this->dispatchEventOnImport($result, $index);
        });

        $this->customerRepository->flush();
    }

    /**
     * @param mixed[] $result
     * @param CustomerToImportContract $contract
     * @return Customer
     */
    protected function customerCreateOrUpdate(array $result, CustomerToImportContract $contract): Customer
    {
        $importClass = $contract->toImport($result);
        if ($entity = $this->findEntityOrReplace($importClass)) {
            return $contract->toImport($result, $entity);
        }

        return $importClass;
    }

    /**
     * @param Customer $customer
     * @return Customer|object
     */
    protected function findEntityOrReplace(Customer $customer): Customer
    {
        return $this->customerRepository->findOneBy(['email' => $customer->getEmail()]) ?? new $customer;
    }

    /**
     * @param mixed $result
     * @param int $index
     */
    protected function dispatchEventOnImport(array $result, int $index): void
    {
        if ($this->dispatcher !== null) {
            $this->dispatcher->dispatch(CustomerImportEvent::class, compact('result', 'index'));
        }
    }

    /**
     * @return Dispatcher|null
     */
    public function getDispatcher(): ?Dispatcher
    {
        return $this->dispatcher;
    }

    /**
     * @param Dispatcher|null $dispatcher
     * @return CustomerImporter
     */
    public function setDispatcher(?Dispatcher $dispatcher = null): CustomerImporter
    {
        $this->dispatcher = $dispatcher;

        return $this;
    }
}

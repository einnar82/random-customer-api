<?php

namespace App\Modules\Customer\Drivers;

use App\Modules\Customer\Contracts\ImporterContract;
use Illuminate\Support\Collection;
use Illuminate\Http\Client\PendingRequest;

class JsonDriver implements ImporterContract
{
    protected array $config;

    protected PendingRequest $request;

    /**
     * RandomUserJsonDriver constructor.
     * @param mixed[] $config
     * @param PendingRequest $request
     */
    public function __construct(PendingRequest $request, array $config)
    {
        $this->config = $config;
        $this->request = $request;
    }

    /**
     * @param mixed[] $options
     * @return Collection
     */
    public function results(array $options = []): Collection
    {
        $request = $this->request->get(
            $this->config['version'],
            $this->generateQueryParams($options)
        );
        return new Collection($request->json('results'));
    }

    /**
     * @param mixed[] $options
     * @return mixed[]
     */
    private function generateQueryParams(array $options): array
    {
        return array_merge($this->config['query'], [
            'results' => (int) ($options['count'] ?? $this->config['query']['results']),
            'inc' => implode(',', $this->config['query']['inc']),
            'format' => 'json'
        ]);
    }
}

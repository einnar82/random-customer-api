<?php

namespace App\Modules\Customer\Contracts;

interface CustomerImporterContract
{
    /**
     * @param $contract
     * @param array $options
     */
    public function import($contract, array $options = []) : void;
}

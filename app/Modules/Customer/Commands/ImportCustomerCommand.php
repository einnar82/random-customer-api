<?php

namespace App\Modules\Customer\Commands;

use App\Modules\Customer\Contracts\CustomerImporterContract;
use App\Modules\Customer\Events\CustomerImportEvent;
use App\Modules\Customer\Models\CustomerImportModel;
use Illuminate\Console\Command;
use Illuminate\Contracts\Events\Dispatcher;
use Symfony\Component\Console\Helper\ProgressBar;

class ImportCustomerCommand extends Command
{

    /**
     * @var string
     */
    protected $description = 'Import users based on the given drivers';

    /**
     * @var string
     */
    protected $signature = 'customer:import
                            {--c|count=100 : Count of users to import}
                            {--d|driver=json : Driver to use}';

    /**
     * @param CustomerImporterContract $importer
     * @param Dispatcher $dispatcher
     */
    public function handle(CustomerImporterContract $importer, Dispatcher $dispatcher): void
    {
        if ($this->option('count') > 0) {
            $this->importData($importer, $dispatcher);
        } else {
            $this->error("Must be a positive number.");
        }
    }

    /**
     * @param Dispatcher $dispatcher
     * @param ProgressBar $bar
     */
    protected function advanceProgressBar(Dispatcher $dispatcher, ProgressBar $bar): void
    {
        $dispatcher->listen(CustomerImportEvent::class, function () use ($bar) {
            $bar->advance();
        });
    }

    /**
     * @param CustomerImporterContract $importer
     * @param Dispatcher $dispatcher
     */
    protected function importData(CustomerImporterContract $importer, Dispatcher $dispatcher): void
    {
        $count = $this->option('count');
        $driver = $this->option('driver');
        $bar = $this->output->createProgressBar($count);
        $bar->start();
        $this->advanceProgressBar($dispatcher, $bar);
        $importer->import(CustomerImportModel::class, compact('count', 'driver'));
        $bar->finish();
        $this->newLine();
        $this->line("Successfully imported $count customer(s)");
    }
}

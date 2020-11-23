<?php

namespace App\Console\Commands;

use App\Repositories\Eloquent\SupplierBalanceRecordRepository;
use App\Services\BillMessageService;
use Illuminate\Console\Command;

class DayConsumeCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'day_consume:auto';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '日消费';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        app(SupplierBalanceRecordRepository::class)->dayConsumeDeduction();
    }
}

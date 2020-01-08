<?php

namespace App\Console\Commands;

use App\Services\BillMessageService;
use Illuminate\Console\Command;

class BillMessage extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bill_message:auto';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '账单消息通知';

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
        app(BillMessageService::class)->deadlineNewSupplierBill();
    }
}
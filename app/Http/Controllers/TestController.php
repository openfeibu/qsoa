<?php

namespace App\Http\Controllers;

use App\Repositories\Eloquent\SupplierBalanceRecordRepository;
use App\Services\BillMessageService;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class TestController extends BaseController
{
    public function __construct
    (
        BillMessageService $billMessageService
    )
    {
        $this->billMessageService = $billMessageService;
    }

    public function test()
    {
       // $this->billMessageService->deadlineSoonNewSupplierBill();
        //$this->billMessageService->overdueNewSupplierBill();
        //$this->billMessageService->deadlineSoonUnpaidSupplierBill();
       // $this->billMessageService->lessThanAirportPayTotal();
        app(SupplierBalanceRecordRepository::class)->dayConsumeDeduction();
    }
}

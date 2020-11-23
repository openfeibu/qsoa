<?php
namespace App\Services;

use App\Exceptions\RequestSuccessException;
use App\Models\AirlineBill;
use App\Models\Airport;
use App\Models\Supplier;
use App\Models\SupplierBalanceRecord;
use App\Models\SupplierBill;
use App\Repositories\Eloquent\AirlineBillRepository;
use App\Repositories\Eloquent\SupplierBalanceRecordRepository;
use App\Repositories\Eloquent\MessageRepository;
use App\Repositories\Eloquent\SupplierBillRepository;
use App\Repositories\Eloquent\SupplierRepository;
use Illuminate\Http\Request;
use Log,DB;

class BalanceService
{
    public function __construct
    (
        SupplierBillRepository $supplierBillRepository,
        AirlineBillRepository $airlineBillRepository,
        MessageRepository $messageRepository,
        SupplierBalanceRecordRepository $supplierBalanceRecordRepository,
        SupplierRepository $supplierRepository
    )
    {
        $this->supplierBillRepository = $supplierBillRepository;
        $this->airlineBillRepository = $airlineBillRepository;
        $this->messageRepository = $messageRepository;
        $this->supplierBalanceRecordRepository = $supplierBalanceRecordRepository;
        $this->supplierRepository = $supplierRepository;
    }


    /* 6 天 内未审核的，通知航空公司管理员*/
    public function deadlineSoonNewSupplierBill()
    {
        $date = date("Y-m-d", strtotime("-6 day"));

        $supplier_bill_count = SupplierBill::whereRaw("DATE_FORMAT(created_at,'%Y-%m-%d') = '".$date."'")->where('status','new')->count();

        if($supplier_bill_count)
        {
            $content = '你有'.$supplier_bill_count.'条供应商账单明天是最后审核期限，请尽快完成审核';
            $this->messageRepository->createMessage([
                'admin_group' => config('model.airline_user.airline_user.model.model'),
                'content' => $content
            ]);
        }
    }


}
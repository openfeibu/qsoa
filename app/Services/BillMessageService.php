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

class BillMessageService
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
    /* 7 天 内未审核的，通知航空公司管理员*/
    public function deadlineNewSupplierBill()
    {
        $date = date("Y-m-d", strtotime("-7 day"));

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
    /* 超过7天未审核的，通知航空公司管理员*/
    public function  overdueNewSupplierBill()
    {
        $date = date("Y-m-d", strtotime("-7 day"));

        $supplier_bill_count = SupplierBill::whereRaw("DATE_FORMAT(created_at,'%Y-%m-%d') < '".$date."'")->where('status','new')->count();

        if($supplier_bill_count)
        {
            $content = '你有'.$supplier_bill_count.'条供应商账单已经超过7天未审核，请尽快完成审核';
            $this->messageRepository->createMessage([
                'admin_group' => config('model.airline_user.airline_user.model.model'),
                'content' => $content
            ]);
        }
    }

    /* 供应商账单应付日期明天过期，通知供应商管理员 */
    public function deadlineSoonUnpaidSupplierBill()
    {
        $date = date("Y-m-d", strtotime("+1 day"));

        $supplier_bill_count = SupplierBill::whereRaw("pay_date = '".$date."'")->where('pay_status','unpaid')->whereNotIn('status',['invalid'])->count();

        if($supplier_bill_count)
        {
            $content = '你有'.$supplier_bill_count.'条未付款供应商账单明天是最后付款期限，请尽快付款';
            $this->messageRepository->createMessage([
                'admin_group' => config('model.supplier_user.supplier_user.model.model'),
                'content' => $content
            ]);
        }
    }
    /*  供应商账单应付日期今天过期，通知供应商管理员 */
    public function deadlineUnpaidSupplierBill()
    {
        $date = date("Y-m-d");

        $supplier_bill_count = SupplierBill::whereRaw("pay_date = '".$date."'")->where('pay_status','unpaid')->whereNotIn('status',['invalid'])->count();

        if($supplier_bill_count)
        {
            $content = '你有'.$supplier_bill_count.'条未付款供应商账单今天是最后付款期限，请尽快付款';
            $this->messageRepository->createMessage([
                'admin_group' => config('model.supplier_user.supplier_user.model.model'),
                'content' => $content
            ]);
        }
    }
    /* 供应商账单应付日期过期，通知供应商管理员 */
    public function overdueUnpaidSupplierBill()
    {
        $date = date("Y-m-d");

        $supplier_bill_count = SupplierBill::whereRaw("pay_date < '".$date."'")->where('pay_status','unpaid')->whereNotIn('status',['invalid'])->count();

        if($supplier_bill_count)
        {
            $content = '你有'.$supplier_bill_count.'条供应商账单已超过应付日期，请尽快付款';
            $this->messageRepository->createMessage([
                'admin_group' => config('model.supplier_user.supplier_user.model.model'),
                'content' => $content
            ]);
        }
    }

    /* 航空公司账单应付日期明天过期，通知航空公司管理员 */
    public function deadlineSoonUnpaidAirlineBill()
    {
        $date = date("Y-m-d", strtotime("+1 day"));

        $airline_bill_count = AirlineBill::whereRaw("pay_date = '".$date."'")->where('pay_status','unpaid')->whereNotIn('status',['invalid'])->count();

        if($airline_bill_count)
        {
            $content = '你有'.$airline_bill_count.'条未付款航空公司账单明天是最后应收款期限，请尽快收款';
            $this->messageRepository->createMessage([
                'admin_group' => config('model.airline_user.airline_user.model.model'),
                'content' => $content
            ]);
        }
    }

    /* 航空公司账单应付日期今天过期，通知航空公司管理员 */
    public function deadlineUnpaidAirlineBill()
    {
        $date = date("Y-m-d");

        $airline_bill_count = AirlineBill::whereRaw("pay_date = '".$date."'")->where('pay_status','unpaid')->whereNotIn('status',['invalid'])->count();

        if($airline_bill_count)
        {
            $content = '你有'.$airline_bill_count.'条未付款航空公司账单今天是最后应收款期限，请尽快收款';
            $this->messageRepository->createMessage([
                'admin_group' => config('model.airline_user.airline_user.model.model'),
                'content' => $content
            ]);
        }
    }

    /* 航空公司账单应付日期过期，通知航空公司管理员 */
    public function overdueUnpaidAirlineBill()
    {
        $date = date("Y-m-d");

        $airline_bill_count = AirlineBill::whereRaw("pay_date < '".$date."'")->where('pay_status','unpaid')->whereNotIn('status',['invalid'])->count();

        if($airline_bill_count)
        {
            $content = '你有'.$airline_bill_count.'条未付款航空公司账单已超过应收款日期，请尽快收款';
            $this->messageRepository->createMessage([
                'admin_group' => config('model.airline_user.airline_user.model.model'),
                'content' => $content
            ]);
        }
    }

    /* 机场余额不足7、3、1天 */

    public function lessThanAirportPayTotal()
    {
        $airports = Airport::orderBy('id','desc')->get(['id','name','balance']);
        foreach ($airports as $ey => $airport)
        {
            $average = $this->supplierBalanceRecordRepository->average($airport->id);
            $day = 0;
            if($airport['balance'] < $average){
                $day = 1;
            }else if($airport['balance'] < $average * 3){
                $day = 3;
            }else if($airport['balance'] < $average * 7){
                $day = 7;
            }
            if($day)
            {
                $content = trans('messages.less_than_day_pay_total',['airport_name' => $airport->name,'day' => $day ]);
                $this->messageRepository->createMessage([
                    'admin_group' => config('model.user.admin.model.model'),
                    'content' => $content
                ]);
                $this->messageRepository->createMessage([
                    'admin_group' => config('model.supplier_user.supplier_user.model.model'),
                    'content' => $content
                ]);
            }
        }
    }

    /* 供应商余额不足7、3、1天 */
    public function lessThanSupplierPayTotal()
    {
        $suppliers = Supplier::orderBy('id','desc')->get(['id','name','balance','day_consume']);
        foreach ($suppliers as $ey => $supplier)
        {
            $average = $supplier->day_consume;
            $day = 0;
            if($supplier['balance'] < $average){
                $day = 1;
            }else if($supplier['balance'] < $average * 3){
                $day = 3;
            }else if($supplier['balance'] < $average * 7){
                $day = 7;
            }
            if($day)
            {
                $content = trans('messages.supplier_less_than_day_pay_total',['supplier_name' => $suppliers->name,'day' => $day ]);
                $this->messageRepository->createMessage([
                    'admin_group' => config('model.user.admin.model.model'),
                    'content' => $content
                ]);
                $this->messageRepository->createMessage([
                    'admin_group' => config('model.supplier_user.supplier_user.model.model'),
                    'content' => $content
                ]);
            }
        }
    }

}
<?php

namespace App\Http\Controllers\Airline;

use App\Models\SupplierBill;
use App\Models\AirlineBill;
use Route,Auth;
use App\Http\Controllers\Airline\Controller as BaseController;
use App\Traits\AdminUser\AdminUserPages;
use App\Http\Response\ResourceResponse;
use App\Traits\Theme\ThemeAndViews;
use App\Traits\AdminUser\RoutesAndGuards;

class ResourceController extends BaseController
{
    use AdminUserPages,ThemeAndViews,RoutesAndGuards;

    public function __construct()
    {
        parent::__construct();

        if (!empty(app('auth')->getDefaultDriver())) {
            $this->middleware('auth:' . app('auth')->getDefaultDriver());
           // $this->middleware('role:' . $this->getGuardRoute());
            $this->middleware('permission:' .Route::currentRouteName());
            $this->middleware('active');

        }
        $this->response = app(ResourceResponse::class);
        $this->setTheme();
    }
    /**
     * Show dashboard for each user.
     *
     * @return \Illuminate\Http\Response
     */
    public function home()
    {
        $airline_id = Auth::user()->airline_id;

        //供应商账单数
        $supplier_bill_count = SupplierBill::count();
        //待审核
        $supplier_bill_new_count = SupplierBill::whereIn('status',['new'])->count();
        //待出账
        $supplier_bill_pass_count = SupplierBill::whereIn('status',['passed','rebill'])->count();
        //已作废
        $supplier_bill_invalid_count = SupplierBill::whereIn('status',['invalid'])->count();
        //已出账
        $supplier_bill_bill_count = SupplierBill::whereIn('status',['bill'])->count();
        //已完成
        $supplier_bill_finished_count = SupplierBill::whereIn('status',['finished'])->count();

        //航空公司账单数
        $airline_bill_count = AirlineBill::where('airline_id',$airline_id)->count();
        //待结算
        $airline_bill_new_count = AirlineBill::where('airline_id',$airline_id)->whereIn('status',['new'])->count();
        //已结算
        $airline_bill_finished_count = AirlineBill::where('airline_id',$airline_id)->whereIn('status',['finished'])->count();
        //已作废
        $airline_bill_invalid_count = AirlineBill::where('airline_id',$airline_id)->whereIn('status',['invalid'])->count();

        return $this->response->title(trans('app.admin.panel'))
            ->data(compact('supplier_bill_count','supplier_bill_new_count','supplier_bill_pass_count','supplier_bill_invalid_count','supplier_bill_bill_count','supplier_bill_finished_count','airline_bill_count','airline_bill_new_count','airline_bill_finished_count','airline_bill_invalid_count'))
            ->view('home')
            ->output();
    }
    public function dashboard()
    {
        return $this->response->title('测试')
            ->view('dashboard')
            ->output();
    }
}

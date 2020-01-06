<?php

namespace App\Http\Controllers\Admin;

use App\Models\Airline;
use App\Models\AirlineBill;
use App\Models\Airport;
use App\Models\Supplier;
use App\Models\SupplierBill;
use Route;
use App\Http\Controllers\Admin\Controller as BaseController;
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
        $airline_bill_count = AirlineBill::count();
        //待结算
        $airline_bill_new_count = AirlineBill::whereIn('status',['new'])->count();
        //已结算
        $airline_bill_finished_count = AirlineBill::whereIn('status',['finished'])->count();
        //已作废
        $airline_bill_invalid_count = AirlineBill::whereIn('status',['invalid'])->count();

        $airport_count =Airport::count();
        $airline_count = Airline::count();
        $supplier_count = Supplier::count();

        return $this->response->title(trans('app.admin.panel'))
            ->data(compact('supplier_bill_count','supplier_bill_new_count','supplier_bill_pass_count','supplier_bill_invalid_count','supplier_bill_bill_count','supplier_bill_finished_count','airline_bill_count','airline_bill_new_count','airline_bill_finished_count','airline_bill_invalid_count','airport_count','airline_count','supplier_count'))
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

<?php

namespace App\Repositories\Eloquent;

use App\Exceptions\OutputServerMessageException;
use App\Models\AirlineBillItemInfo;
use Auth;
use App\Models\AirlineBill;
use App\Models\AirlineBillItem;
use App\Models\AirlineBillRecord;
use App\Repositories\Eloquent\AirlineBillRepositoryInterface;
use App\Repositories\Eloquent\BaseRepository;
use PhpOffice\PhpWord\TemplateProcessor;

class AirlineBillRepository extends BaseRepository implements AirlineBillRepositoryInterface
{
    public function boot()
    {
        $this->fieldSearchable = config('model.airline.airline_bill.search');
    }

    public function model()
    {
        return config('model.airline.airline_bill.model');
    }
    public function operation($data)
    {
        try{
            AirlineBill::where('id',$data['id'])->update([
                'status' => $data['status'],
            ]);
            AirlineBillRecord::create([
                'airline_bill_id' => $data['id'],
                'admin_id' => Auth::user()->id,
                'admin_name' => Auth::user()->name,
                'admin_model' => get_admin_model(Auth::user()),
                'status' => $data['status'],
                'content' => $data['content'] ?? ''
            ]);
            if($data['status'] == 'invalid')
            {
                $this->refund($data['id']);
            }
            app(OperationRepository::class)->createOperation([
                'operationable_id' => $data['id'],
                'operationable_type' => config('model.airline.airline_bill.model'),
                'content' => trans('airline_bill.status.operation.'.$data['status']),
            ]);
            return true;
        }catch (Exception $e) {
            throw new OutputServerMessageException($e->getMessage());
        }
    }
    public function getAirlineBillFromSupplierBill($infos)
    {
        $item = [
            'usg' => '',
            'usd_usg' => '',
            'sum' => '',
        ];
        foreach ($infos as $key => $info)
        {
            switch ($info['field_mark'])
            {
                case 'USG':
                    $item['usg'] = $info['field_value'];
                    break;
                case 'USD/USG':
                    $item['price'] = $info['field_value'];
                    break;
                case 'SUM':
                    $item['sum'] = $info['field_value'];
                    break;
            }
        }
        return $item;
    }
    public function refund($id)
    {
        $supplier_bill = AirlineBill::where('id',$id)->first();
        if($supplier_bill->pay_status == 'paid')
        {
            AirlineBill::where('id',$id)->update([
                'pay_status' => 'refund',
            ]);
        }
    }
    public function airlineBillItems($airline_bill_id)
    {
        $airline_bill_items = AirlineBillItem::where('airline_bill_id',$airline_bill_id)
            ->orderBy('flight_date','asc')
            ->get();
        foreach ($airline_bill_items as $key => $airline_bill_item)
        {
            $fields = AirlineBillItemInfo::where('airline_bill_item_id',$airline_bill_item->id)->orderBy('id','asc')->get();
            $airline_bill_item->fields = $fields->toArray();
        }
        return $airline_bill_items;
    }
    /*
    public function airlineBillItems($airline_bill_id)
    {
        $airline_bill_items = AirlineBillItem::join('supplier_bill_items','supplier_bill_items.id','=','airline_bill_items.supplier_bill_item_id')
            ->where('airline_bill_id',$airline_bill_id)
            ->orderBy('supplier_bill_items.flight_date','asc')
            ->get(['supplier_bill_items.flight_date','supplier_bill_items.flight_number','supplier_bill_items.board_number','supplier_bill_items.order_number','supplier_bill_items.num_of_orders','supplier_bill_items.unit','airline_bill_items.mt','airline_bill_items.usg','airline_bill_items.price','airline_bill_items.total','airline_bill_items.id']);

        return $airline_bill_items;
    }
    */
    public function downloadWord($airline_bill)
    {
        $airline =  app(AirlineRepository::class)->where('id',$airline_bill->airline_id)->first();
        if(!$airline)
        {
            throw new OutputServerMessageException("航空公司不存在");
        }
        $airport =  app(AirportRepository::class)->where('id',$airline_bill->airport_id)->first();
        if(!$airport)
        {
            throw new OutputServerMessageException("机场不存在");
        }

        $supplier_bill = app(SupplierBillRepository::class)->find($airline_bill->supplier_bill_id);

        $document = new TemplateProcessor(storage_path('uploads/system/airline_bill_word.docx'));


        $document->setValue('airline_name', $airline->name);
        $document->setValue('airline_address', $airline->address);
        $document->setValue('airline_code', $airline->code);

        $document->setValue('agreement_no', $airline_bill->agreement_no);
        $document->setValue('supply_start_date', date('d.m.Y',strtotime($supplier_bill->supply_start_date)));
        $document->setValue('supply_end_date', date('d.m.Y',strtotime($supplier_bill->supply_end_date)));
        $document->setValue('issuing_date', date('d.m.Y',strtotime($airline_bill->issuing_date)));
        $document->setValue('week',date("l",strtotime($airline_bill->issuing_date)));

        $document->setValue('airport_code', $airport->code);
        $document->setValue('usg', $airline_bill->usg);
        $document->setValue('price', $airline_bill->price);
        $document->setValue('total', $airline_bill->total);
        $document->setValue('usd_total', umoney($airline_bill->total));
        $document->setValue('tax', $airline_bill->tax);
        $document->setValue('incl_tax', $airline_bill->incl_tax);

        $document->setValue('address', setting('word_address'));

        if(date('j',strtotime($supplier_bill->supply_end_date)) <=15)
        {
            $month_desc = '上半月';
        }else{
            $month_desc = '下半月';
        }
        $title = date('Y',strtotime($supplier_bill->supply_start_date)).'年'.date('n',strtotime($supplier_bill->supply_start_date)).'月'.$month_desc.' '.$airport->code.' 机场加油汇总表';
        $document->setValue('title', $title);

        $name = $airline_bill->sn.'('.date('YmdHis').').docx';
        $path = storage_path('uploads/word/'.$name);
        $document->saveAs($path);

        return response()->download($path,$name,$headers = ['Content-Type'=>'application/zip;charset=utf-8']);
    }
}
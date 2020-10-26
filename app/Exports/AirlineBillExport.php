<?php

namespace App\Exports;

use App\Exceptions\OutputServerMessageException;
use App\Repositories\Eloquent\AirlineBillItemInfoRepository;
use App\Repositories\Eloquent\AirportRepository;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\Exportable;
use App\Repositories\Eloquent\AirlineBillItemRepository;
use App\Repositories\Eloquent\AirlineBillRepository;
use App\Repositories\Eloquent\AirlineRepository;
use App\Repositories\Eloquent\SupplierBillRepository;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\BeforeExport;
use Maatwebsite\Excel\Events\BeforeWriting;
use Maatwebsite\Excel\Events\BeforeSheet;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\RegistersEventListeners;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class AirlineBillExport implements FromCollection,WithEvents
{

    use RegistersEventListeners;

    //行数
    public $count=0;
    //列数
    public $list_count=0;

    public $airport_merge_count = 0;

    public function __construct($airline_bill)
    {
        $this->airline_bill = $airline_bill;
    }

    public function collection()
    {
        $airline_bill = $this->airline_bill;
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

        //$contract = $airline->contracts->where('airport_id',$airline_bill->airport_id)->first();

        $supplier_bill = app(SupplierBillRepository::class)->find($airline_bill->supplier_bill_id);

        $airline_bill_items = app(AirlineBillRepository::class)->airlineBillItems($airline_bill->id);


        if(date('j',strtotime($supplier_bill->supply_end_date)) <=15)
        {
            $month_desc = '上半月';
        }else{
            $month_desc = '下半月';
        }
        $title = date('Y',strtotime($supplier_bill->supply_start_date)).'年'.date('n',strtotime($supplier_bill->supply_start_date)).'月'.$month_desc.' '.$airport->code.' 机场加油汇总表';


        $count = $airline_bill_items->count();
        $this->count = $count + 10;
        $usd_total = umoney($airline_bill->total);
        $airline_bill_data = [
            [$title],
            ['DATE 日期','Airport机场','加油量（L）','Price单价（美元）','Sum总金额  （美元）','Tax','Incl.Tax USD'],
            [ date('d.m.Y',strtotime($supplier_bill['supply_start_date']))."-". date('d.m.Y',strtotime($supplier_bill['supply_end_date'])),$airport->code,$airline_bill->litre,$airline_bill->price,$airline_bill->total,$airline_bill->tax,$airline_bill->incl_tax],
            ['TOTAL：'.$usd_total],
            ['']
        ];

        $basics_fields = ['Flight date'];
        $info_fields = app(AirlineBillItemInfoRepository::class)->fields($airline_bill->id);
        $info_fields_count = count($info_fields);
        $fields = array_merge($basics_fields,$info_fields,['L','MT','USG','unit','Price','Amount,USD']);

        $statement_fields = ['Supplier Company','','Customer Company','Country / City','','Airport / Code'];

        $this->airport_merge_count = $airport_merge_count = count($fields)-8;
        for($j=0;$j<$airport_merge_count;$j++)
        {
            array_push($statement_fields,'');
        }
        $statement_fields = array_merge($statement_fields,['Start Date','End Date']);

        $airline_bill_item_data = [
            ['STATEMENT OF ACCOUNT'],
            $statement_fields,
            [setting('company_name'),'',$airline->name,$airline->country.'/'.$airline->city,'',$airport->name.'/'.$airport->code,'','',date('Y/m/d',strtotime($supplier_bill->supply_start_date)),date('Y/m/d',strtotime($supplier_bill->supply_end_date))],
            $fields
        ];

        $this->list_count = count($fields);
        $i = 4;
        foreach ($airline_bill_items as $key => $airline_bill_item)
        {
            $airline_bill_item_data[$i] = [
               date('Y/m/d',strtotime($airline_bill_item['flight_date']))
            ];
            foreach ($airline_bill_item->fields as $k => $field)
            {
                array_push($airline_bill_item_data[$i],$field['field_value']);
            }

            array_push($airline_bill_item_data[$i],$airline_bill_item['litre'],$airline_bill_item['mt'],$airline_bill_item['usg'],$airline_bill_item['unit'],$airline_bill_item['price'],$airline_bill_item['total']);

            $i++;
        }

        $airline_bill_item_data[$i] = [
            'Total'
        ];
        for($j=0;$j<$info_fields_count;$j++)
        {
            array_push($airline_bill_item_data[$i],'');
        }
        $airline_bill_item_data[$i] = array_merge($airline_bill_item_data[$i],[$airline_bill->litre,$airline_bill->mt,$airline_bill->usg,'','',$airline_bill->total]);

        $data = array_merge($airline_bill_data,$airline_bill_item_data);
        return  new Collection($data);

    }
    /*
    public function view(): View
    {
        $airline =  app(AirlineRepository::class)->find($this->airline_bill->airline_id);

        $airport =  app(AirportRepository::class)->find($this->airline_bill->airport_id);

        $contract = $airline->contracts->where('airport_id',$this->airline_bill->airport_id)->first();

        $supplier_bill = app(SupplierBillRepository::class)->find($this->airline_bill->supplier_bill_id);

        $airline_bill_items = app(AirlineBillRepository::class)->airlineBillItems($this->airline_bill->id);

        return view('exports.airline_bill', compact('supplier_bill','airline_bill','airline_bill_items','contract','airport'));

    }
    */
    public function registerEvents(): array
    {
        return [
            AfterSheet::class  => function(AfterSheet $event) {

                //设置列宽
                //$event->sheet->getDelegate()->getColumnDimension('A')->setWidth(50);
                //设置行高，$i为数据行数
                for ($i = 0; $i<=$this->count; $i++) {
                    $event->sheet->getDelegate()->getRowDimension($i)->setRowHeight(30);
                }
                $last_letter = letters()[$this->list_count-1];

                //设置区域单元格垂直居中
                $event->sheet->getDelegate()->getStyle('A1:'.$last_letter.$this->count)->getAlignment()->setVertical('center');

                $event->sheet->getDelegate()->getStyle('A1:'.$last_letter.$this->count)->getAlignment()->setHorizontal('center');

                $event->sheet->getDelegate()->getStyle('A4:'.$last_letter.'4')->getAlignment()->setHorizontal('left');

                $event->sheet->getDelegate()->getRowDimension(2)->setRowHeight(50);

                $event->sheet->getDelegate()->getStyle('A1:'.$last_letter.'1')->getFont()->setSize('14');
                $event->sheet->getDelegate()->getStyle('A6:'.$last_letter.'6')->getFont()->setSize('14');
                //设置区域单元格字体、颜色、背景等，其他设置请查看 applyFromArray 方法，提供了注释

                $event->sheet->getDelegate()->getStyle('A2:G2')->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => 'thin',
                        ],
                    ],
                    'fill' => [
                        'fillType' => 'linear', //线性填充，类似渐变
                        'rotation' => 45, //渐变角度
                        'startColor' => [
                            'rgb' => '1DAAE4' //初始颜色
                        ],
                        //结束颜色，如果需要单一背景色，请和初始颜色保持一致
                        'endColor' => [
                            'argb' => '1DAAE4'
                        ]
                    ]
                ]);
                $event->sheet->getDelegate()->getStyle('A4:G4')->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => 'thin',
                        ],
                    ],
                    'fill' => [
                        'fillType' => 'linear', //线性填充，类似渐变
                        'rotation' => 45, //渐变角度
                        'startColor' => [
                            'rgb' => 'FFFD38' //初始颜色
                        ],
                        //结束颜色，如果需要单一背景色，请和初始颜色保持一致
                        'endColor' => [
                            'argb' => 'FFFD38'
                        ]
                    ]
                ]);


                $event->sheet->getDelegate()->getStyle('A6:'.$last_letter.'9')->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => 'thin',
                        ],
                    ],
                    'fill' => [
                        'fillType' => 'linear', //线性填充，类似渐变
                        'rotation' => 45, //渐变角度
                        'startColor' => [
                            'rgb' => '1DAAE4' //初始颜色
                        ],
                        //结束颜色，如果需要单一背景色，请和初始颜色保持一致
                        'endColor' => [
                            'argb' => '1DAAE4'
                        ]
                    ]
                ]);
                $event->sheet->getDelegate()->getStyle('A'.$this->count.':'.$last_letter.$this->count)->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => 'thin',
                        ],
                    ],
                    'fill' => [
                        'fillType' => 'linear', //线性填充，类似渐变
                        'rotation' => 45, //渐变角度
                        'startColor' => [
                            'rgb' => '1DAAE4' //初始颜色
                        ],
                        //结束颜色，如果需要单一背景色，请和初始颜色保持一致
                        'endColor' => [
                            'argb' => '1DAAE4'
                        ]
                    ]
                ]);

                $event->sheet->getDelegate()->getStyle('A1:G4')->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => 'thin',
                        ],
                    ],
                ]);
                $event->sheet->getDelegate()->getStyle('A6:'.$last_letter.$this->count)->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => 'thin',
                        ],
                    ],
                ]);

                $event->sheet->getDelegate()->mergeCells('A1:G1');
                $event->sheet->getDelegate()->mergeCells('A4:G4');
                $event->sheet->getDelegate()->mergeCells('A6:'.$last_letter.'6');
                $event->sheet->getDelegate()->mergeCells('A7:B7');
                $event->sheet->getDelegate()->mergeCells('A8:B8');
                $event->sheet->getDelegate()->mergeCells('D7:E7');
                $event->sheet->getDelegate()->mergeCells('D8:E8');

                $airport_merge_letter = letters()[$this->airport_merge_count+5];
                $event->sheet->getDelegate()->mergeCells('F7:'.$airport_merge_letter.'7');

                $event->sheet->getDelegate()->mergeCells('F8:'.$airport_merge_letter.'8');

                for ($i = 0; $i<=$this->list_count; $i++) {
                    $event->sheet->getDelegate()->getColumnDimension(letters()[$i])->setWidth(20);
                }
            }
        ];
    }
}
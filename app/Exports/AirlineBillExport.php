<?php

namespace App\Exports;

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

class AirlineBillExport implements FromCollection,WithEvents,ShouldAutoSize
{

    use RegistersEventListeners;

    public $count = 0;

    public function __construct($airline_bill)
    {
        $this->airline_bill = $airline_bill;
    }

    public function collection()
    {
        $airline_bill = $this->airline_bill;
        $airline =  app(AirlineRepository::class)->find($airline_bill->airline_id);

        $airport =  app(AirportRepository::class)->find($airline_bill->airport_id);

        $contract = $airline->contracts->where('airport_id',$airline_bill->airport_id)->first();

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
        $airline_bill_data = [
            [$title],
            ['DATE 日期','Airport机场','加油量（USG）','Price单价（美元）','Sum总金额  （美元）','Tax','Incl.Tax USD'],
            [ date('d.m.Y',strtotime($supplier_bill['supply_start_date']))."-". date('d.m.Y',strtotime($supplier_bill['supply_end_date'])),$airport->code,$airline_bill->usg,$airline_bill->price,$airline_bill->total,$airline_bill->tax,$airline_bill->incl_tax],
            ['TOTAL：EIGHTY FOUR THOUSAND AND SEVEN HUNDRED FORTY AND CENTS SEVENTY FOUR	'],
            ['']
        ];

        $airline_bill_item_data = [
            ['STATEMENT OF ACCOUNT'],
            ['Supplier Company','','Customer Company','Country / City','','Airport / Code','','','Start Date','End Date'],
            [setting('company_name'),'',$airline->name,$airline->country.'/'.$airline->city,'',$airport->name.'/'.$airport->code,'','',date('Y/m/d',strtotime($supplier_bill->supply_start_date)),date('Y/m/d',strtotime($supplier_bill->supply_end_date))],
            ['Flight date','Flight number','Board number','Order number','Num. of orders','MT','USG','unit','Price','Amount,USD']
        ];

        $i = 4;
        foreach ($airline_bill_items as $key => $airline_bill_item)
        {
            $airline_bill_item_data[$i] = [
               date('Y/m/d',strtotime($airline_bill_item['flight_date'])),date('Y/m/d',strtotime($airline_bill_item['flight_number'])),$airline_bill_item['board_number'],$airline_bill_item['order_number'],$airline_bill_item['num_of_orders'],$airline_bill_item['mt'],$airline_bill_item['usg'],$airline_bill_item['unit'],$airline_bill_item['price'],$airline_bill_item['total']
            ];
            $i++;
        }
        $airline_bill_item_data[$i] = [
            'Total','','','','',$airline_bill->mt,$airline_bill->usg,'','',$airline_bill->total
        ];

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
                //设置区域单元格垂直居中
                $event->sheet->getDelegate()->getStyle('A1:J'.$this->count)->getAlignment()->setVertical('center');

                $event->sheet->getDelegate()->getStyle('A1:J'.$this->count)->getAlignment()->setHorizontal('center');

                $event->sheet->getDelegate()->getStyle('A4:J4')->getAlignment()->setHorizontal('left');

                $event->sheet->getDelegate()->getRowDimension(2)->setRowHeight(50);

                $event->sheet->getDelegate()->getStyle('A1:J1')->getFont()->setSize('14');
                $event->sheet->getDelegate()->getStyle('A6:J6')->getFont()->setSize('14');
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


                $event->sheet->getDelegate()->getStyle('A6:J9')->applyFromArray([
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
                $event->sheet->getDelegate()->getStyle('A'.$this->count.':J'.$this->count)->applyFromArray([
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
                $event->sheet->getDelegate()->getStyle('A6:J'.$this->count)->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => 'thin',
                        ],
                    ],
                ]);

                $event->sheet->getDelegate()->mergeCells('A1:G1');
                $event->sheet->getDelegate()->mergeCells('A4:G4');
                $event->sheet->getDelegate()->mergeCells('A6:J6');
                $event->sheet->getDelegate()->mergeCells('A7:B7');
                $event->sheet->getDelegate()->mergeCells('A8:B8');
                $event->sheet->getDelegate()->mergeCells('D7:E7');
                $event->sheet->getDelegate()->mergeCells('D8:E8');
                $event->sheet->getDelegate()->mergeCells('F7:H7');
                $event->sheet->getDelegate()->mergeCells('F8:H8');
            }
        ];
    }
}
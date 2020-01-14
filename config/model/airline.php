<?php

return [

/*
 * Modules .
 */
    'modules'  => ['airport','can_cooperative_airline_airport'],


/*
 * Views for the page  .
 */
    'views'    => ['default' => 'Default', 'left' => 'Left menu', 'right' => 'Right menu'],

// Modale variables for page module.
    'airline'     => [
        'model'        => 'App\Models\Airline',
        'table'        => 'airlines',
        'primaryKey'   => 'id',
        'hidden'       => [],
        'visible'      => [],
        'guarded'      => ['*'],
        'fillable'     => ['name','leader','tel','email','position','country','country_id','province','province_id','city','city_id','address','created_at','updated_at'],
        'translate'    => [],
        'upload_folder' => '/airport',
        'encrypt'      => ['id'],
        'revision'     => ['name'],
        'perPage'      => '20',
        'search'        => [
            'name'  => 'like',
            'id' => '=',
        ],
    ],
    'can_cooperative_airline_airport'     => [
        'model'        => 'App\Models\CanCooperativeAirlineAirport',
        'table'        => 'can_cooperative_airline_airport',
        'primaryKey'   => 'id',
        'hidden'       => [],
        'visible'      => [],
        'guarded'      => ['*'],
        'fillable'     => ['airline_id','airport_id','created_at','updated_at'],
        'translate'    => [],
        'upload_folder' => '/airport',
        'encrypt'      => ['id'],
        'revision'     => ['name'],
        'perPage'      => '20',
        'search'        => [
            'title'  => 'name',
        ],
    ],

    'airline_bill'     => [
        'model'        => 'App\Models\AirlineBill',
        'table'        => 'airline_bills',
        'primaryKey'   => 'id',
        'hidden'       => [],
        'visible'      => [],
        'guarded'      => ['*'],
        'fillable'     => ['sn','supplier_bill_id','agreement_no','airline_id','airline_name','airport_id','airport_name','supplier_id','supplier_name','mt','usg','price','tax','incl_tax','total','issuing_date','status','pay_status','pay_date','paid_date','paid_total','created_at','updated_at'],
        'translate'    => [],
        'status_button' => [
            'new' => 'layui-btn-primary',
            'passed' => 'layui-btn-normal',
            'rejected' => 'layui-btn-warm',
            'invalid' => 'layui-btn-danger',
            'modified' => 'layui-btn-normal',
            'finished' => 'layui-btn-normal',
        ],
        'pay_status_button' => [
            'unpaid' => 'layui-btn-primary',
            'paid' => 'layui-btn-normal',
            'refund' => 'layui-btn-danger',
        ],
        'upload_folder' => '/airline',
        'encrypt'      => ['id'],
        'revision'     => ['sn'],
        'perPage'      => '20',
        'search'        => [
            'sn'  => 'like',
            'agreement_no' => 'like',
            'airline_id' => '=',
            'airport_id' => '=',
            'supplier_id' => '=',
        ],
    ],
    'airline_bill_item'     => [
        'model'        => 'App\Models\AirlineBillItem',
        'table'        => 'airline_bill_items',
        'primaryKey'   => 'id',
        'hidden'       => [],
        'visible'      => [],
        'guarded'      => ['*'],
        'fillable'     => ['airline_bill_id','supplier_bill_id','supplier_bill_item_id','airline_id','airline_name','airport_id','airport_name','supplier_id','supplier_name','date','mt','usg','price','total','created_at','updated_at'],
        'translate'    => [],
        'upload_folder' => '/airline',
        'encrypt'      => ['id'],
        'revision'     => ['sn'],
        'perPage'      => '20',
        'search'        => [
            'airline_bill_id' => '=',
            'supplier_bill_id' => '=',
        ],
    ],
    'airline_bill_record'     => [
        'model'        => 'App\Models\AirlineBillRecord',
        'table'        => 'airline_bill_records',
        'primaryKey'   => 'id',
        'hidden'       => [],
        'visible'      => [],
        'guarded'      => ['*'],
        'fillable'     => ['admin_id','admin_name','admin_model','airline_bill_id','status','content'],
        'translate'    => [],
        'upload_folder' => '/airline',
        'encrypt'      => ['id'],
        'revision'     => ['sn'],
        'perPage'      => '20',
        'search'        => [
            'sn'  => 'like',
            'date' => '='
        ],
    ],
];

<?php

return [

/*
 * Modules .
 */
    'modules'  => ['supplier','can_cooperative_supplier_airport','supplier_bill_template','supplier_bill_template_field'],

/*
 * Views for the page  .
 */
    'views'    => ['default' => 'Default', 'left' => 'Left menu', 'right' => 'Right menu'],

// Modale variables for page module.
    'supplier'     => [
        'model'        => 'App\Models\Supplier',
        'table'        => 'suppliers',
        'primaryKey'   => 'id',
        'hidden'       => [],
        'visible'      => [],
        'guarded'      => ['*'],
        'fillable'     => ['name','leader','email','tel','position','country','country_id','province','province_id','city','city_id'],
        'translate'    => [],
        'upload_folder' => '/supplier',
        'encrypt'      => ['id'],
        'revision'     => ['name'],
        'perPage'      => '20',
        'search'        => [
            'title'  => 'name',
        ],
    ],
    'can_cooperative_supplier_airport'     => [
        'model'        => 'App\Models\CanCooperativeSupplierAirport',
        'table'        => 'can_cooperative_supplier_airport',
        'primaryKey'   => 'id',
        'hidden'       => [],
        'visible'      => [],
        'guarded'      => ['*'],
        'fillable'     => ['supplier_id','airport_id','created_at','updated_at'],
        'translate'    => [],
        'upload_folder' => '/supplier',
        'encrypt'      => ['id'],
        'revision'     => ['name'],
        'perPage'      => '20',
        'search'        => [
        ],
    ],
    'supplier_bill_template' => [
        'model'        => 'App\Models\SupplierBillTemplate',
        'table'        => 'supplier_bill_templates',
        'primaryKey'   => 'id',
        'hidden'       => [],
        'visible'      => [],
        'guarded'      => ['*'],
        'fillable'     => ['supplier_id','created_at','updated_at'],
        'translate'    => [],
        'upload_folder' => '/supplier',
        'encrypt'      => ['id'],
        'revision'     => ['name'],
        'perPage'      => '20',
        'search'        => [
        ],
    ],
    'supplier_bill_template_field' => [
        'model'        => 'App\Models\SupplierBillTemplateField',
        'table'        => 'supplier_bill_template_fields',
        'primaryKey'   => 'id',
        'hidden'       => [],
        'visible'      => [],
        'guarded'      => ['*'],
        'fillable'     => ['supplier_id','field','field_type','field_comment','field_default','field_mark','order','created_at','updated_at'],
        'field_mark' => [
            'USG','USD/USG','SUM'
        ],
        'translate'    => [],
        'upload_folder' => '/supplier',
        'encrypt'      => ['id'],
        'revision'     => ['name'],
        'perPage'      => '20',
        'search'        => [
        ],
    ],
    'supplier_bill'     => [
        'model'        => 'App\Models\SupplierBill',
        'table'        => 'supplier_bills',
        'primaryKey'   => 'id',
        'hidden'       => [],
        'visible'      => [],
        'guarded'      => ['*'],
        'fillable'     => ['sn','invoice_date','supplier_id','supplier_name','airport_id','airport_name','airline_id','airline_name','total','status','pay_date','paid_date','paid_total','pay_status'],
        'translate'    => [],
        'status_button' => [
            'new' => 'layui-btn-primary',
            'passed' => 'layui-btn-normal',
            'rejected' => 'layui-btn-warm',
            'invalid' => 'layui-btn-danger',
            'modified' => 'layui-btn-normal',
            'bill' => 'layui-btn-normal',
            'rebill' => 'layui-btn-normal',
            'finished' => 'layui-btn-normal',
        ],
        'pay_status_button' => [
            'unpaid' => 'layui-btn-primary',
            'paid' => 'layui-btn-normal',
            'refund' => 'layui-btn-danger',
        ],
        'upload_folder' => '/supplier',
        'encrypt'      => ['id'],
        'revision'     => ['sn'],
        'perPage'      => '20',
        'search'        => [
            'sn'  => 'like',
            'invoice_date' => '=',
            'pay_date' => '=',
            'status' => '=',
        ],
    ],
    'supplier_bill_item'     => [
        'model'        => 'App\Models\SupplierBillItem',
        'table'        => 'supplier_bill_items',
        'primaryKey'   => 'id',
        'hidden'       => [],
        'visible'      => [],
        'guarded'      => ['*'],
        'fillable'     => ['flight_date','supplier_bill_id','supplier_id','supplier_name','airport_id','airport_name','airline_id','airline_name','total'],
        'translate'    => [],
        'status_button' => [
            'new' => 'layui-btn-primary',
            'passed' => 'layui-btn-normal',
            'rejected' => 'layui-btn-warm',
            'invalid' => 'layui-btn-danger',
            'modified' => 'layui-btn-normal',
            'bill' => 'layui-btn-normal',
            'rebill' => 'layui-btn-normal',
            'finished' => 'layui-btn-normal',
        ],
        'upload_folder' => '/supplier',
        'encrypt'      => ['id'],
        'revision'     => ['sn'],
        'perPage'      => '20',
        'search'        => [
            'sn'  => 'like',
            'invoice_date' => '=',
            'flight_date' => '=',
            'status' => '=',
        ],
    ],
    'supplier_bill_record'     => [
        'model'        => 'App\Models\SupplierBillRecord',
        'table'        => 'supplier_bill_records',
        'primaryKey'   => 'id',
        'hidden'       => [],
        'visible'      => [],
        'guarded'      => ['*'],
        'fillable'     => ['admin_id','admin_name','admin_model','supplier_bill_id','status','content'],
        'translate'    => [],
        'upload_folder' => '/supplier',
        'encrypt'      => ['id'],
        'revision'     => ['sn'],
        'perPage'      => '20',
        'search'        => [
            'sn'  => 'like',
            'date' => '='
        ],
    ],
    'supplier_bill_item_info'     => [
        'model'        => 'App\Models\SupplierBillItemInfo',
        'table'        => 'supplier_bill_item_infos',
        'primaryKey'   => 'id',
        'hidden'       => [],
        'visible'      => [],
        'guarded'      => ['*'],
        'fillable'     => ['supplier_bill_item_id','supplier_bill_template_field_id','field','field_comment','field_value','field_mark','order','created_at','updated_at'],
        'translate'    => [],
        'upload_folder' => '/supplier',
        'encrypt'      => ['id'],
        'revision'     => ['sn'],
        'perPage'      => '20',
        'search'        => [
            'sn'  => 'like',
            'date' => '='
        ],
    ],
];

<?php

return [
    'name'        => '供应商账单',
    'names'       => '供应商账单',
    'label'       => [
        'sn' => '编号',
        'invoice_date' => '账单日期',
        'pay_date' => '应付款日期',
        'paid_date' => '实际付款日期',
        'paid_total' => '实际付款金额',
        'total' => '应付款',
        'pay_status' => '付款状态'
    ],
    'message'     => [
        'nopage' => 'Page not found.',
        'forbid_airline_bill' => '账单状态异常！',
        'create_repeat_airline_or_airport' => '请确保机场统一及航空公司统一！',
    ],
    'title' => '供应商账单管理',
    'add' => '生成供应商账单',
    'status' => [
        'one-level' => [
            'new' => '未审核',
            'passed' => '已审核',
            //'rejected' => '待修改',
            'invalid' => '已作废',
            //'modified' => '已修改',
            'bill' => '已出账',
            'rebill' => '重出账',
            'finished' => '已完成',
        ],
        'two-level' => [
            'new' => '待审核',
            'passed' => '待出账',
            //'rejected' => '已驳回',
            'invalid' => '已作废',
            //'modified' => '待再审',
            'bill' => '已出账',
            'rebill' => '重出账',
            'finished' => '已完成',
        ],
    ],
    'pay_status' => [
        'unpaid' => '待付款',
        'paid' => '已付款',
        'refund' => '已退款',
    ],

];

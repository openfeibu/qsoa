<?php

return [
    'name'        => '航空公司账单',
    'names'       => '航空公司账单',
    'label'       => [
        'sn' => '编号',
        'agreement_no' => '合同编号',
        'issuing_date' => '开证日期',
        'date_of_supply' => '供应日期',
        'total' => '原总价',
        'pay_date' => '应到账日期',
        'final_total' => '应到账金额',
        'pay_total' => '应到账金额',
        'status' => '状态',
        'pay_status' => '付款状态',
        'paid_date' => '实际到账日期',
        'paid_total' => '实际到账金额',
    ],
    'message'     => [
        'nopage' => 'Page not found.',
    ],
    'title' => '航空公司账单管理',
    'add' => '生成航空公司账单',
    'status' => [
        'one-level' => [
            'new' => '待结算',
            'invalid' => '已作废',
            'modified' => '已修改',
            'finished' => '已结算',
        ],
        'two-level' => [
            'new' => '待结算',
            'invalid' => '已作废',
            'modified' => '已修改',
            'finished' => '已结算',
        ],
        'actions' => [
            'finish' => '结算',
        ],
        'operation' => [
            'new' => '新建',
            'invalid' => '作废',
            'finished' => '结算',
        ],
    ],
    'pay_status' => [
        'unpaid' => '待收款',
        'paid' => '已收款',
        'refund' => '已退款',
    ],
];

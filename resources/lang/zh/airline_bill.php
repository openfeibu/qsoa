<?php

return [
    'name'        => '航空公司账单',
    'names'       => '航空公司账单',
    'label'       => [
        'sn' => '编号',
        'agreement_no' => '合同编号',
        'issuing_date' => '开证日期',
        'mt' => 'MT',
        'usg' => 'USG',
        'price' => '单价',
        'total' => '应收款金额',
        'pay_date' => '应收款日期',
        'status' => '状态',
        'pay_status' => '付款状态',
        'paid_date' => '实际收款日期',
        'paid_total' => '实际收款金额',
        'tax' => 'Tax',
        'incl_tax' => 'Incl.Tax',
    ],
    'message'     => [
        'nopage' => 'Page not found.',
    ],
    'title' => '航空公司账单管理',
    'add' => '导入航空公司账单',
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

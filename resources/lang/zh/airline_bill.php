<?php

return [
    'name'        => '航空公司账单',
    'names'       => '航空公司账单',
    'label'       => [
        'sn' => '编号',
        'agreement_no' => '合同编号',
        'issuing_date' => '开证日期',
        'mt' => 'MT or L',
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
        'remark' => '备注',
    ],
    'message'     => [
        'nopage' => 'Page not found.',
    ],
    'title' => '航空公司账单管理',
    'add' => '导入航空公司账单',
    'status' => [
        'one-level' => [
            'new' => '待收款',
            'invalid' => '已作废',
            'modified' => '已修改',
            'finished' => '已收款',
        ],
        'two-level' => [
            'new' => '待收款',
            'invalid' => '已作废',
            'modified' => '已修改',
            'finished' => '已收款',
        ],
        'actions' => [
            'finish' => '收款',
        ],
        'operation' => [
            'new' => '新建',
            'invalid' => '作废',
            'finished' => '收款',
        ],
    ],
    'pay_status' => [
        'unpaid' => '待收款',
        'paid' => '已收款',
        'refund' => '已退款',
    ],
];

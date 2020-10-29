<?php

return [

/*
 * Modules .
 */
    'modules'  => ['exchange_rate'],


/*
 * Views for the page  .
 */
    'views'    => ['default' => 'Default', 'left' => 'Left menu', 'right' => 'Right menu'],

// Modale variables for page module.
    'exchange_rate'     => [
        'model'        => 'App\Models\ExchangeRate',
        'table'        => 'exchange_rate',
        'primaryKey'   => 'id',
        'hidden'       => [],
        'visible'      => [],
        'guarded'      => ['*'],
        'fillable'     => ['currencyCode','timeStamp','close','count','high','low','open','releaseType','priceUnit','source','date'],
        'translate'    => [],
        'upload_folder' => '/exchange_rate',
        'encrypt'      => ['id'],
        'revision'     => ['name'],
        'perPage'      => '20',
        'search'        => [
            'currencyCode'  => 'like',
            'close' => 'like',
            'count' => 'like',
            'high' => 'like',
            'low' => 'like',
            'open' => 'like',
            'releaseType' => 'like',
            'priceUnit' => 'like',
            'source' => 'like',
            'date' => '=',
        ],
    ],

];

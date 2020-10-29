<?php

return [

/*
 * Modules .
 */
    'modules'  => ['currency'],


/*
 * Views for the page  .
 */
    'views'    => ['default' => 'Default', 'left' => 'Left menu', 'right' => 'Right menu'],

// Modale variables for page module.
    'currency'     => [
        'model'        => 'App\Models\Currency',
        'table'        => 'currency',
        'primaryKey'   => 'id',
        'hidden'       => [],
        'visible'      => [],
        'guarded'      => ['*'],
        'fillable'     => ['currencyCode'],
        'translate'    => [],
        'upload_folder' => '/exchange_rate',
        'encrypt'      => ['id'],
        'revision'     => ['name'],
        'perPage'      => '20',
        'search'        => [
            'currencyCode'  => '=',
        ],
    ],

];

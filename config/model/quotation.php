<?php

return [

/*
 * Modules .
 */
    'modules'  => ['quotation'],


/*
 * Views for the page  .
 */
    'views'    => ['default' => 'Default', 'left' => 'Left menu', 'right' => 'Right menu'],

// Modale variables for page module.
    'quotation'     => [
        'model'        => 'App\Models\Quotation',
        'table'        => 'quotations',
        'primaryKey'   => 'id',
        'hidden'       => [],
        'visible'      => [],
        'guarded'      => ['*'],
        'fillable'     => ['name','file','airport_id','billing_date'],
        'translate'    => [],
        'upload_folder' => '/quotation',
        'encrypt'      => ['id'],
        'revision'     => ['name'],
        'perPage'      => '20',
        'search'        => [
            'name'  => 'like',
            'billing_date' => '=',
            'airport_id' => '=',
        ],
    ],

];

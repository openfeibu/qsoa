<?php

return [

/*
 * Modules .
 */
    'modules'  => ['jinqi'],


/*
 * Views for the page  .
 */
    'views'    => ['default' => 'Default', 'left' => 'Left menu', 'right' => 'Right menu'],

// Modale variables for page module.
    'jinqi'     => [
        'model'        => 'App\Models\Jinqi',
        'table'        => 'jinqi',
        'primaryKey'   => 'id',
        'hidden'       => [],
        'visible'      => [],
        'guarded'      => ['*'],
        'fillable'     => [],
        'translate'    => [],
        'upload_folder' => '/jinqi',
        'encrypt'      => ['id'],
        'revision'     => ['name'],
        'perPage'      => '20',
        'search'        => [
            'symbolCode'  => 'like',
            'symbolName' => 'like',
            'symbolChnName' => 'like',
            'FDate' => 'like',
            'FOpen' => 'like',
            'FHigh' => 'like',
            'FLow' => 'like',
            'FClose' => 'like',
            'FVolume' => 'like',
            'FInterest' => 'like',
            'FUnit' => 'like',
        ],
    ],

];

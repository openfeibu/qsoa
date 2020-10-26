<?php

return [

/*
 * Modules .
 */
    'modules'  => ['link'],


/*
 * Views for the page  .
 */
    'views'    => ['default' => 'Default', 'left' => 'Left menu', 'right' => 'Right menu'],

// Modale variables for page module.
    'continent'     => [
        'model'        => 'App\Models\Continent',
        'table'        => 'continents',
        'primaryKey'   => 'id',
        'hidden'       => [],
        'visible'      => [],
        'guarded'      => ['*'],
        'fillable'     => ['name', 'en_name'],
        'translate'    => [],
        'upload_folder' => '',
        'encrypt'      => ['id'],
        'revision'     => ['name'],
        'perPage'      => '20',
        'search'        => [
            'name'  => 'like',
        ],
    ],

];

<?php

return [

/*
 * Modules .
 */
    'modules'  => ['message'],


/*
 * Views for the page  .
 */
    'views'    => ['default' => 'Default', 'left' => 'Left menu', 'right' => 'Right menu'],

// Modale variables for page module.
    'message'     => [
        'model'        => 'App\Models\Link',
        'table'        => 'messages',
        'primaryKey'   => 'id',
        'hidden'       => [],
        'visible'      => [],
        'guarded'      => ['*'],
        'fillable'     => ['admin_group', 'admin_id', 'airline_id','supplier_id','content','level','read','url','created_at','updated_at'],
        'translate'    => ['name', 'image', 'order'],
        'upload_folder' => '/page/message',
        'encrypt'      => ['id'],
        'revision'     => ['name'],
        'perPage'      => '20',
        'search'        => [
            'title'  => 'like',
        ],
    ],

];

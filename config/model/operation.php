<?php

return [

/*
 * Modules .
 */
    'modules'  => ['operation'],


/*
 * Views for the page  .
 */
    'views'    => ['default' => 'Default', 'left' => 'Left menu', 'right' => 'Right menu'],

// Modale variables for page module.
    'operation'     => [
        'model'        => 'App\Models\operation',
        'table'        => 'operations',
        'primaryKey'   => 'id',
        'hidden'       => [],
        'visible'      => [],
        'guarded'      => ['*'],
        'fillable'     => ['admin_id','admin_name','admin_model','operationable_id','operationable_type','content'],
        'translate'    => [],
        'upload_folder' => '/operation',
        'encrypt'      => ['id'],
        'revision'     => ['name'],
        'perPage'      => '20',
        'search'        => [
            'name'  => 'like',
        ],
    ],


];

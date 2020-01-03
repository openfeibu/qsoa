<?php

return [

/*
 * Modules .
 */
    'modules'  => ['contract'],


/*
 * Views for the page  .
 */
    'views'    => ['default' => 'Default', 'left' => 'Left menu', 'right' => 'Right menu'],

// Modale variables for page module.
    'contract'     => [
        'model'        => 'App\Models\Contract',
        'table'        => 'contracts',
        'primaryKey'   => 'id',
        'hidden'       => [],
        'visible'      => [],
        'guarded'      => ['*'],
        'fillable'     => ['name','start_time','end_time','airport_id','contractable_type','contractable_id','increase_price'],
        'translate'    => [],
        'upload_folder' => '/contract',
        'encrypt'      => ['id'],
        'revision'     => ['name'],
        'perPage'      => '20',
        'search'        => [
            'name'  => 'like',
        ],
    ],
    'contract_image'     => [
        'model'        => 'App\Models\ContractImage',
        'table'        => 'contract_images',
        'primaryKey'   => 'id',
        'hidden'       => [],
        'visible'      => [],
        'guarded'      => ['*'],
        'fillable'     => ['contract_id','url','order'],
        'translate'    => [],
        'upload_folder' => '/contract',
        'encrypt'      => ['id'],
        'revision'     => ['name'],
        'perPage'      => '20',
        'search'        => [
        ],
    ],

];

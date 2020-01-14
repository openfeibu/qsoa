<?php

return [

/*
 * Modules .
 */
    'modules'  => ['airport'],


/*
 * Views for the page  .
 */
    'views'    => ['default' => 'Default', 'left' => 'Left menu', 'right' => 'Right menu'],

// Modale variables for page module.
    'airport'     => [
        'model'        => 'App\Models\Airport',
        'table'        => 'airports',
        'primaryKey'   => 'id',
        'hidden'       => [],
        'visible'      => [],
        'guarded'      => ['*'],
        'fillable'     => ['name','code','country','country_id','province','province_id','city','city_id','leader','content','created_at','updated_at'],
        'translate'    => [],
        'upload_folder' => '/airport',
        'encrypt'      => ['id'],
        'revision'     => ['name'],
        'perPage'      => '20',
        'search'        => [
            'name'  => 'like',
            'id' => '=',
        ],
    ],
    'can_cooperative_airline_airport'     => [
        'model'        => 'App\Models\CanCooperativeAirlineAirport',
        'table'        => 'can_cooperative_airline_airport',
        'primaryKey'   => 'id',
        'hidden'       => [],
        'visible'      => [],
        'guarded'      => ['*'],
        'fillable'     => ['airline_id','airport_id','created_at','updated_at'],
        'translate'    => [],
        'upload_folder' => '/airport',
        'encrypt'      => ['id'],
        'revision'     => ['name'],
        'perPage'      => '20',
        'search'        => [
            'title'  => 'name',
        ],
    ],

];

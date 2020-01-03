<?php

return [
    /*
     * Package.
     */
    'package'  => 'airline_user',

    /*
     * Modules.
     */
    'modules'  => ['airline_user'],
    /*
     * Additional user types other than user.
     */
    'types'    => ['client'],

    'policies' => [
        // Bind User policy
    ],
    'airline_user'     => [
        'model' => [
            'model'         => \App\Models\AirlineUser::class,
            'table'         => 'airline_users',
            //'presenter'     => \Litepie\User\Repositories\Presenter\UserPresenter::class,
            'hidden'        => [],
            'visible'       => [],
            'guarded'       => ['*'],
            'slugs'         => [],
            'dates'         => ['created_at', 'updated_at', 'deleted_at', 'dob'],
            'appends'       => [],
            'fillable'      => ['name', 'email', 'password', 'api_token', 'remember_token', 'phone','airline_id'],
            'translate'     => [],
            'upload_folder' => 'airline_user',
            'uploads'       => [
                'photo' => [
                    'count' => 1,
                    'type'  => 'image',
                ],
            ],
            'casts'         => [
                'permissions' => 'array',
                'photo'       => 'array',
                'dob'         => 'date',
            ],
            'revision'      => [],
            'perPage'       => '20',
            'search'        => [
                'name'        => 'like',
                'email'       => 'like',
                'phone'      => 'like',
                'street'      => 'like',
                'status'      => 'like',
                'created_at'  => 'like',
                'updated_at'  => 'like',
            ],
        ],

    ],

];

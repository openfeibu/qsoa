<?php

return [
    /*
     * Package.
     */
    'package'  => 'finance_user',

    /*
     * Modules.
     */
    'modules'  => ['finance_user'],
    /*
     * Additional user types other than user.
     */
    'types'    => ['client'],

    'policies' => [
        // Bind User policy
    ],
    'finance_user'     => [
        'model' => [
            'model'         => \App\Models\AirlineUser::class,
            'table'         => 'finance_users',
            //'presenter'     => \Litepie\User\Repositories\Presenter\UserPresenter::class,
            'hidden'        => [],
            'visible'       => [],
            'guarded'       => ['*'],
            'slugs'         => [],
            'dates'         => ['created_at', 'updated_at', 'deleted_at', 'dob'],
            'appends'       => [],
            'fillable'      => ['name', 'email', 'password', 'api_token', 'remember_token', 'phone'],
            'translate'     => [],
            'upload_folder' => 'finance_user',
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

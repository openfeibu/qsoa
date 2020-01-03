<?php

return [
    /*
     * Package.
     */
    'package'  => 'supplier_user',

    /*
     * Modules.
     */
    'modules'  => ['supplier_user'],
    /*
     * Additional user types other than user.
     */
    'types'    => ['client'],

    'policies' => [
        // Bind User policy
    ],
    'supplier_user'     => [
        'model' => [
            'model'         => \App\Models\SupplierUser::class,
            'table'         => 'supplier_users',
            //'presenter'     => \Litepie\User\Repositories\Presenter\UserPresenter::class,
            'hidden'        => [],
            'visible'       => [],
            'guarded'       => ['*'],
            'slugs'         => [],
            'dates'         => ['created_at', 'updated_at', 'deleted_at', 'dob'],
            'appends'       => [],
            'fillable'      => ['name', 'email', 'password', 'api_token', 'remember_token', 'phone','supplier_id'],
            'translate'     => [],
            'upload_folder' => 'supplier_user',
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

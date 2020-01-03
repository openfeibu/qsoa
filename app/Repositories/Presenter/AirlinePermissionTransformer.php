<?php

namespace App\Repositories\Transformers;

use League\Fractal\TransformerAbstract;

/**
 * Class PermissionTransformer
 * @package namespace App\Transformers;
 */
class AirlinePermissionTransformer extends TransformerAbstract
{

    /**
     * Transform the \Permission entity
     * @param \App\Models\AirlinePermission $permission
     *
     * @return array
     */
    public function transform(\App\Models\AirlinePermission $permission)
    {
        return [
            'id'         => (int) $permission->id,
        ];
    }
}

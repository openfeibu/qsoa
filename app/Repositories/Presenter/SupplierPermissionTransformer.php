<?php

namespace App\Repositories\Transformers;

use League\Fractal\TransformerAbstract;

/**
 * Class PermissionTransformer
 * @package namespace App\Transformers;
 */
class SupplierPermissionTransformer extends TransformerAbstract
{

    /**
     * Transform the \Permission entity
     * @param \App\Models\SupplierPermission $permission
     *
     * @return array
     */
    public function transform(\App\Models\SupplierPermission $permission)
    {
        return [
            'id'         => (int) $permission->id,
        ];
    }
}

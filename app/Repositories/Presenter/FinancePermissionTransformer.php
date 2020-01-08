<?php

namespace App\Repositories\Transformers;

use League\Fractal\TransformerAbstract;

/**
 * Class PermissionTransformer
 * @package namespace App\Transformers;
 */
class FinancePermissionTransformer extends TransformerAbstract
{

    /**
     * Transform the \Permission entity
     * @param \App\Models\FinancePermission $permission
     *
     * @return array
     */
    public function transform(\App\Models\FinancePermission $permission)
    {
        return [
            'id'         => (int) $permission->id,
        ];
    }
}

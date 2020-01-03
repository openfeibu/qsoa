<?php

namespace App\Traits\SupplierRoles;

use Illuminate\Support\Str;

trait Slugable
{
    /**
     * Set slug attribute.
     *
     * @param string $value
     * @return void
     */
    public function setSlugAttribute($value)
    {
        $this->attributes['slug'] = Str::slug($value, config('model.provider_roles.separator'));
    }
}

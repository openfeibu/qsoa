<?php

namespace App\Traits;


trait Area
{
    public function getAreaAttribute()
    {
        $address = $this->attributes['address'] ?? '';
        $areas = $this->attributes['city'] ? [
            $this->attributes['country'],
            $this->attributes['city'],
            $address
        ]:[
            $this->attributes['country'],
            $this->attributes['province'],
            $address
        ];
        return implode('/',array_filter($areas));
    }

}

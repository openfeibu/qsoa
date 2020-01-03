<?php

namespace App\Traits;


trait Area
{
    public function getAreaAttribute()
    {
        $areas = $this->attributes['city'] ? [
            $this->attributes['country'],
            $this->attributes['city'],
        ]:[
            $this->attributes['country'],
            $this->attributes['province'],
        ];
        return implode('/',array_filter($areas));
    }

}

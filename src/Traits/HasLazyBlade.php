<?php

namespace captenmasin\LazyBlade\Traits;

trait HasLazyBlade
{
    protected $appends = ['namespace'];

    public function toArray()
    {
        $array = parent::toArray();
        $array['namespace'] = $this->namespace;
        return $array;
    }

    public function getNamespaceAttribute()
    {
        return get_class($this);
    }
}

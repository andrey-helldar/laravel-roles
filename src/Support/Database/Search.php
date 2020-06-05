<?php

namespace Helldar\Roles\Support\Database;

use Helldar\Roles\Models\BaseModel;
use Illuminate\Database\Eloquent\Builder as Eloquent;
use Illuminate\Support\Arr;

class Search
{
    /**
     * @param  \Illuminate\Database\Eloquent\Builder  $builder
     * @param  \Helldar\Roles\Models\BaseModel|string|int  $values
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function by(Eloquent $builder, $values): Eloquent
    {
        $value = $this->toArray($values);

        return $builder
            ->whereIn('id', $value)
            ->orWhereIn('name', $value);
    }

    /**
     * @param  \Helldar\Roles\Models\BaseModel|string|int  $value
     *
     * @return int|string
     */
    public function getId($value)
    {
        return $value instanceof BaseModel
            ? $value->id
            : $value;
    }

    protected function toArray($values): array
    {
        return array_map(function ($value) {
            return $this->getId($value);
        }, Arr::wrap($values));
    }
}

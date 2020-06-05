<?php

namespace Helldar\Roles\Support\Database;

use Helldar\Roles\Models\BaseModel;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;

class Search
{
    /**
     * @param  \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Relations\Relation  $builder
     * @param  \Helldar\Roles\Models\BaseModel|string|int  $values
     *
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Relations\Relation
     */
    public function by($builder, $values)
    {
        $value = $this->map($values);

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

    /**
     * @param  Collection|Arrayable|array|string|int  $values
     *
     * @return array
     */
    protected function map($values): array
    {
        return array_map(function ($value) {
            return $this->getId($value);
        }, $this->toArray($values));
    }

    protected function toArray($values): array
    {
        if ($this->isArrayable($values)) {
            $values = $values->toArray();
        } elseif (! $this->isArray($values)) {
            $values = Arr::wrap($values);
        }

        return Arr::flatten($values);
    }

    protected function isArray($values): bool
    {
        return is_array($values);
    }

    protected function isArrayable($values): bool
    {
        return $values instanceof Arrayable || $values instanceof Collection;
    }
}

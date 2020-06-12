<?php

namespace Helldar\Roles\Support\Database;

use Helldar\Roles\Models\BaseModel;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;

class Search
{
    /**
     * @param  \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Relations\Relation  $builder
     * @param  \Helldar\Roles\Models\BaseModel|int|string  $values
     *
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Relations\Relation
     */
    public function by($builder, $values)
    {
        $value = $this->map($values);

        $nums  = $this->filterNum($value);
        $slugs = $this->filterSlugs($value);

        if ($nums && ! $slugs) {
            return $builder->whereIn('id', $nums);
        }

        if (! $nums && $slugs) {
            return $builder->whereIn('slug', $slugs);
        }

        return $builder->where(function (Builder $builder) use ($nums, $slugs) {
            $builder
                ->whereIn('id', $nums)
                ->orWhereIn('slug', $slugs);
        });
    }

    /**
     * @param  \Helldar\Roles\Models\BaseModel|int|string  $value
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
     * @param  array|Arrayable|Collection|int|string  $values
     *
     * @return array
     */
    protected function map($values): array
    {
        return array_map(function ($value) {
            return $this->getId($value);
        }, $this->toArray($values));
    }

    protected function filterNum(array $values): array
    {
        return array_filter($values, function ($value) {
            return is_numeric($value);
        });
    }

    protected function filterSlugs(array $values): array
    {
        return array_filter($values, function ($value) {
            return ! is_numeric($value);
        });
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

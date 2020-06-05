<?php

namespace Helldar\Roles\Facades\Database;

use Helldar\Roles\Models\BaseModel;
use Helldar\Roles\Support\Database\Search as SearchSupport;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Facades\Facade;

/**
 * @method static Builder|Relation by(Builder|Relation $builder, BaseModel|BaseModel[]|int|int[]|string|string[] $values)
 * @method static string|int getId(Builder|int|string $value)
 */
class Search extends Facade
{
    protected static function getFacadeAccessor()
    {
        return SearchSupport::class;
    }
}

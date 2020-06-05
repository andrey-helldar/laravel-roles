<?php

namespace Helldar\Roles\Facades\Database;

use Helldar\Roles\Models\BaseModel;
use Helldar\Roles\Support\Database\Search as SearchSupport;
use Illuminate\Support\Facades\Facade;

/**
 * @method static \Illuminate\Database\Eloquent\Builder by(\Illuminate\Database\Eloquent\Builder $builder, BaseModel|string|int $values)
 * @method static string|int getId(\Illuminate\Database\Eloquent\Builder|string|int $value)
 */
class Search extends Facade
{
    protected static function getFacadeAccessor()
    {
        return SearchSupport::class;
    }
}

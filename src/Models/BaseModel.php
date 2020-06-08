<?php

namespace Helldar\Roles\Models;

use Helldar\Roles\Facades\Config;
use Helldar\Roles\Facades\Database\Search;
use Helldar\Roles\Traits\Searchable;
use Helldar\Roles\Traits\SetAttribute;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $slug
 *
 * @method static Model|self create(array $values)
 * @method static Builder|self searchBy(string $value)
 */
abstract class BaseModel extends Model
{
    use SetAttribute;
    use Searchable;

    public $timestamps = false;

    protected $fillable = ['slug'];

    public function __construct(array $attributes = [])
    {
        $this->setConnection(
            Config::connection()
        );

        parent::__construct($attributes);
    }

    /**
     * @param  \Illuminate\Database\Eloquent\Builder  $builder
     * @param  \Helldar\Roles\Models\BaseModel|int|string  $value
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected function scopeSearchBy(Builder $builder, $value)
    {
        return Search::by($builder, $value);
    }
}

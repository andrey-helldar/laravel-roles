<?php

namespace Helldar\Roles\Models;

use Helldar\Roles\Facades\Config;
use Helldar\Roles\Traits\Searchable;
use Helldar\Roles\Traits\SetAttribute;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $name
 *
 * @method static \Illuminate\Database\Eloquent\Model|self create(array $values)
 * @method static \Illuminate\Database\Eloquent\Builder|self searchBy(string $value)
 */
abstract class BaseModel extends Model
{
    use SetAttribute;
    use Searchable;

    public $timestamps = false;

    protected $fillable = ['name'];

    public function __construct(array $attributes = [])
    {
        $this->setConnection(
            Config::connection()
        );

        parent::__construct($attributes);
    }

    protected function scopeSearchBy(Builder $builder, string $value)
    {
        return $builder
            ->where('id', $value)
            ->orWhere('name', $value);
    }
}

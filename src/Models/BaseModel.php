<?php

namespace Helldar\Roles\Models;

use Helldar\Roles\Facades\Config;
use Helldar\Roles\Traits\Searchable;
use Helldar\Roles\Traits\SetAttribute;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $name
 *
 * @method static \Illuminate\Database\Eloquent\Model|self create(array $values)
 */
abstract class BaseModel extends Model
{
    use SetAttribute;
    use Searchable;

    protected $fillable = ['name'];

    public function __construct(array $attributes = [])
    {
        $this->setConnection(
            Config::connection()
        );

        parent::__construct($attributes);
    }
}

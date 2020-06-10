<?php

namespace Helldar\Roles\Models;

use Helldar\Roles\Facades\Config;
use Helldar\Roles\Traits\Searchable;
use Helldar\Roles\Traits\SetAttribute;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $slug
 * @property string $title
 *
 * @method static Model|self create(array $values)
 */
abstract class BaseModel extends Model
{
    use SetAttribute;
    use Searchable;

    public $timestamps = false;

    protected $fillable = ['slug', 'title'];

    public function __construct(array $attributes = [])
    {
        $this->setConnection(
            Config::connection()
        );

        parent::__construct($attributes);
    }
}

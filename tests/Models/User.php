<?php

namespace Tests\Models;

use Helldar\Roles\Facades\Config;
use Helldar\Roles\Traits\HasRoles;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasRoles;

    protected $fillable = ['name', 'email', 'password'];

    public function __construct(array $attributes = [])
    {
        $this->setConnection(
            Config::connection()
        );

        parent::__construct($attributes);
    }
}

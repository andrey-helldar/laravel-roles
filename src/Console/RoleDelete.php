<?php

namespace Helldar\Roles\Console;

use Exception;
use Helldar\Roles\Exceptions\UnknownModelKeyException;
use Helldar\Roles\Models\Role;
use Helldar\Roles\Traits\Commands;
use Illuminate\Console\Command;

use function sprintf;

class RoleDelete extends Command
{
    use Commands;

    protected $signature = 'acl:role-delete {name|ID or role name}';

    protected $description = 'Deleting a role';

    /**
     * @throws UnknownModelKeyException
     */
    public function handle()
    {
        if ($this->roleIsDoesntExists()) {
            $this->error(sprintf('Role "%s" doesn\'t exists!', $this->name()));

            return;
        }

        $this->remove();
    }

    /**
     * @throws UnknownModelKeyException
     * @throws Exception
     */
    private function remove()
    {
        /** @var Role $role */
        $model = $this->model('role');

        $this->builder($model)
            ->delete();

        $this->info(sprintf('Role "%s" successfully deleted!', $this->name()));
    }
}

<?php

namespace Helldar\Roles\Console;

use Exception;
use Helldar\Roles\Exceptions\UnknownModelKeyException;
use Helldar\Roles\Models\Role;
use Helldar\Roles\Traits\Commands;
use Illuminate\Console\Command;

use function sprintf;

class RoleCreate extends Command
{
    use Commands;

    protected $signature = 'acl:role-create {name}';

    protected $description = 'Create a new role';

    /**
     * @throws UnknownModelKeyException
     */
    public function handle()
    {
        if ($this->roleIsExists()) {
            $this->error(sprintf('Role "%s" already exists!', $this->name()));

            return;
        }

        $this->create();
    }

    /**
     * @throws UnknownModelKeyException
     * @throws Exception
     */
    protected function create()
    {
        /** @var Role $model */
        $model = $this->model('role');

        $item = $model::create(['name' => $this->name()]);

        $this->info(sprintf('Role "%s" created successfully!', $this->name()));
        $this->line($item);
    }
}

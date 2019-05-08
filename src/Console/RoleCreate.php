<?php

namespace Helldar\Roles\Console;

use Helldar\Roles\Traits\Commands;
use Helldar\Roles\Traits\Find;
use Illuminate\Console\Command;

class RoleCreate extends Command
{
    use Commands, Find;

    protected $signature = 'acl:role-create {name}';

    protected $description = 'Create a new role';

    /**
     * @throws \Helldar\Roles\Exceptions\UnknownModelKeyException
     */
    public function handle()
    {
        if ($this->roleIsExists()) {
            $this->error(\sprintf('Role "%s" already exists!', $this->name()));

            return;
        }

        $this->create();
    }

    /**
     * @throws \Helldar\Roles\Exceptions\UnknownModelKeyException
     * @throws \Exception
     */
    private function create()
    {
        /** @var \Helldar\Roles\Models\Role $model */
        $model = $this->model('role');

        $item = $model::create(['name' => $this->name()]);

        $this->info(\sprintf('Role "%s" created successfully!', $this->name()));
        $this->line($item);
    }
}

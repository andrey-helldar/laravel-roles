<?php

namespace Helldar\Roles\Console;

use Helldar\Roles\Traits\Commands;
use Helldar\Roles\Traits\Models;
use Illuminate\Console\Command;

class PermissionCreate extends Command
{
    use Commands, Models;

    protected $signature = 'acl:permission-create {name}';

    protected $description = 'Create a new permission';

    /**
     * @throws \Helldar\Roles\Exceptions\UnknownModelKeyException
     */
    public function handle()
    {
        if ($this->permissionIsExists()) {
            $this->error(\sprintf('Permission "%s" already exists!', $this->name()));

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
        /** @var \Helldar\Roles\Models\Permission $model */
        $model = $this->model('permission');

        $item = $model::create(['name' => $this->name()]);

        $this->info(\sprintf('Permission "%s" created successfully!', $this->name()));
        $this->line($item);
    }
}

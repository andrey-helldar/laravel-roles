<?php

namespace Helldar\Roles\Console;

use Helldar\Roles\Traits\Commands;
use Illuminate\Console\Command;

class PermissionDelete extends Command
{
    use Commands;

    protected $signature = 'acl:permission-delete {name|ID or permission name}';

    protected $description = 'Deleting a permission';

    /**
     * @throws \Helldar\Roles\Exceptions\UnknownModelKeyException
     */
    public function handle()
    {
        if ($this->permissionIsDoesntExists()) {
            $this->error(\sprintf('Permission "%s" doesn\'t exists!', $this->name()));

            return;
        }

        $this->remove();
    }

    /**
     * @throws \Helldar\Roles\Exceptions\UnknownModelKeyException
     * @throws \Exception
     */
    private function remove()
    {
        /** @var \Helldar\Roles\Models\Permission $model */
        $model = $this->model('permission');

        $this->builder($model)
            ->delete();

        $this->info(\sprintf('Permission "%s" successfully deleted!', $this->name()));
    }
}

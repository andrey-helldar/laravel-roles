<?php

namespace Helldar\Roles\Console;

use Exception;
use Helldar\Roles\Exceptions\UnknownModelKeyException;
use Helldar\Roles\Models\Permission;
use Helldar\Roles\Traits\Commands;
use Illuminate\Console\Command;

use function sprintf;

class PermissionDelete extends Command
{
    use Commands;

    protected $signature = 'acl:permission-delete {name|ID or permission name}';

    protected $description = 'Deleting a permission';

    /**
     * @throws UnknownModelKeyException
     */
    public function handle()
    {
        if ($this->permissionIsDoesntExists()) {
            $this->error(sprintf('Permission "%s" doesn\'t exists!', $this->name()));

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
        /** @var Permission $model */
        $model = $this->model('permission');

        $this->builder($model)
            ->delete();

        $this->info(sprintf('Permission "%s" successfully deleted!', $this->name()));
    }
}

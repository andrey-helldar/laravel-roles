<?php

namespace Helldar\Roles\Console;

use Helldar\Roles\Models\Permission;
use Helldar\Roles\Traits\Commands;
use Illuminate\Console\Command;

class PermissionDelete extends Command
{
    use Commands;

    protected $signature = 'acl:permission-delete {name|ID or permission name}';

    protected $description = 'Deleting a permission';

    public function handle()
    {
        if ($this->permissionIsDoesntExist()) {
            $this->error(sprintf('Permission "%s" doesn\'t exists!', $this->name()));

            return;
        }

        $this->remove();
    }

    protected function remove()
    {
        $name = $this->name();

        $this->searchBuilder(Permission::class, $name)->delete();

        $this->info(sprintf('Permission "%s" successfully deleted!', $name));
    }
}

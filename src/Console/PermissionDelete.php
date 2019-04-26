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

    /**
     * @throws \Exception
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
     * @throws \Exception
     */
    private function remove()
    {
        Permission::query()
            ->whereId($this->name())
            ->orWhereName($this->name())
            ->delete();

        $this->info(\sprintf('Permission "%s" successfully deleted!', $this->name()));
    }
}

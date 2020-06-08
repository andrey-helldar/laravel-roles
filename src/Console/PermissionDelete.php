<?php

namespace Helldar\Roles\Console;

use Helldar\Roles\Models\Permission;
use Helldar\Roles\Traits\Commands;
use Illuminate\Console\Command;

class PermissionDelete extends Command
{
    use Commands;

    protected $signature = 'acl:permission-delete {slug|ID or permission slug}';

    protected $description = 'Deleting a permission';

    public function handle()
    {
        if ($this->permissionIsDoesntExist()) {
            $this->error(sprintf('Permission "%s" doesn\'t exists!', $this->slug()));

            return;
        }

        $this->remove();
    }

    protected function remove()
    {
        $slug = $this->slug();

        $this->searchBuilder(Permission::class, $slug)->delete();

        $this->info(sprintf('Permission "%s" successfully deleted!', $slug));
    }
}

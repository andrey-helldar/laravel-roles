<?php

namespace Helldar\Roles\Console;

use Helldar\Roles\Models\Permission;
use Helldar\Roles\Traits\Commands;
use Illuminate\Console\Command;

class PermissionCreate extends Command
{
    use Commands;

    protected $signature = 'acl:permission-create {slug}';

    protected $description = 'Create a new permission';

    public function handle()
    {
        if ($this->permissionIsExist()) {
            $this->error(sprintf('Permission "%s" already exists!', $this->slug()));

            return;
        }

        $this->create();
    }

    protected function create()
    {
        $slug = $this->slug();
        $item = Permission::create(compact('slug'));

        $this->info(sprintf('Permission "%s" created successfully!', $slug));
        $this->line($item);
    }
}

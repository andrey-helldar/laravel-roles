<?php

namespace Helldar\Roles\Console;

use Helldar\Roles\Models\Permission;
use Helldar\Roles\Traits\Commands;
use Illuminate\Console\Command;

class PermissionCreate extends Command
{
    use Commands;

    protected $signature = 'acl:permission-create {name}';

    protected $description = 'Create a new permission';

    public function handle()
    {
        if ($this->permissionIsExists()) {
            $this->error(\sprintf('Permission "%s" already exists!', $this->name()));

            return;
        }

        $this->create();
    }

    private function create()
    {
        $item = Permission::create(['name' => $this->name()]);

        $this->info(\sprintf('Permission "%s" created successfully!', $this->name()));
        $this->line($item);
    }
}

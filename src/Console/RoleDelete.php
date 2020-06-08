<?php

namespace Helldar\Roles\Console;

use Helldar\Roles\Models\Role;
use Helldar\Roles\Traits\Commands;
use Illuminate\Console\Command;

class RoleDelete extends Command
{
    use Commands;

    protected $signature = 'acl:role-delete {slug|ID or role slug}';

    protected $description = 'Deleting a role';

    public function handle()
    {
        if ($this->roleIsDoesntExist()) {
            $this->error(sprintf('Role "%s" doesn\'t exists!', $this->slug()));

            return;
        }

        $this->remove();
    }

    protected function remove()
    {
        $slug = $this->slug();

        $this->searchBuilder(Role::class, $slug)->delete();

        $this->info(sprintf('Role "%s" successfully deleted!', $slug));
    }
}

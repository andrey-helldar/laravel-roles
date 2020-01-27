<?php

namespace Helldar\Roles\Console;

use Exception;
use Helldar\Roles\Exceptions\UnknownModelKeyException;
use Helldar\Roles\Models\Permission;
use Helldar\Roles\Traits\Commands;
use Illuminate\Console\Command;

use function sprintf;

class PermissionCreate extends Command
{
    use Commands;

    protected $signature = 'acl:permission-create {name}';

    protected $description = 'Create a new permission';

    /**
     * @throws UnknownModelKeyException
     */
    public function handle()
    {
        if ($this->permissionIsExists()) {
            $this->error(sprintf('Permission "%s" already exists!', $this->name()));

            return;
        }

        $this->create();
    }

    /**
     * @throws UnknownModelKeyException
     * @throws Exception
     */
    protected function create()
    {
        /** @var Permission $model */
        $model = $this->model('permission');

        $item = $model::create(['name' => $this->name()]);

        $this->info(sprintf('Permission "%s" created successfully!', $this->name()));
        $this->line($item);
    }
}

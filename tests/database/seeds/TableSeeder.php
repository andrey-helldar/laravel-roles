<?php

namespace Tests\database\seeds;

use function compact;
use Helldar\Roles\Exceptions\UnknownModelKeyException;
use Helldar\Roles\Helpers\Table;
use Helldar\Roles\Models\Permission;
use Helldar\Roles\Models\Role;
use Helldar\Roles\Traits\Find;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

use Tests\Models\User;

class TableSeeder
{
    use Find;

    /**
     * @throws UnknownModelKeyException
     */
    public static function run()
    {
        $class = new self;

        $class->truncate();
        $class->create();
    }

    private function truncate()
    {
        Schema::disableForeignKeyConstraints();

        $tables     = Table::all(true);
        $connection = Table::connection();

        foreach ($tables as $table) {
            DB::connection($connection)->table($table)->truncate();
        }

        Schema::enableForeignKeyConstraints();
    }

    /**
     * @throws UnknownModelKeyException
     */
    private function create()
    {
        /** @var User $user */
        $user = $this->user();

        // Roles
        $role_1 = $this->role('foo');
        $role_2 = $this->role('bar');
        $role_3 = $this->role('baz');
        $role_4 = $this->role('bax');

        // Permissions
        $permission_1 = $this->permission('foo');
        $permission_2 = $this->permission('bar');
        $permission_3 = $this->permission('baz');
        $permission_4 = $this->permission('bax');

        $role_1->syncPermissions([$permission_1->id, $permission_2->id]);
        $role_2->syncPermissions([$permission_1->id, $permission_2->id]);

        $user->syncRoles([$role_1->id, $role_2->id]);
    }

    private function user()
    {
        return User::create([
            'name'     => 'Admin',
            'email'    => 'test@example.com',
            'password' => Hash::make('qwerty'),
        ]);
    }

    /**
     * @param string $name
     *
     * @throws UnknownModelKeyException
     *
     * @return Role
     */
    private function role(string $name)
    {
        /** @var Role|Model $model */
        $model = $this->model('role');

        return $model::create(compact('name'));
    }

    /**
     * @param string $name
     *
     * @throws UnknownModelKeyException
     *
     * @return Permission
     */
    private function permission(string $name)
    {
        /** @var Permission|Model $model */
        $model = $this->model('permission');

        return $model::create(compact('name'));
    }
}

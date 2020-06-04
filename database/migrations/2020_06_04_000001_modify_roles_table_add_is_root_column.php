<?php

use Helldar\Roles\Facades\Config;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Schema\Builder;
use Illuminate\Support\Facades\Schema;

class ModifyRolesTableAddIsRootColumn extends Migration
{
    public function up()
    {
        $this->schema()->table('roles', function (Blueprint $table) {
            $table->boolean('is_root')->default(false)->after('name');
        });
    }

    public function down()
    {
        $this->schema()->table('roles', function (Blueprint $table) {
            $table->dropColumn('is_root');
        });
    }

    protected function schema(): Builder
    {
        return Schema::connection(
            Config::connection()
        );
    }
}

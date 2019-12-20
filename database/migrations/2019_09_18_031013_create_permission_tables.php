<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePermissionTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $tableNames = config('permission.table_names');
        $columnNames = config('permission.column_names');

        Schema::create($tableNames['permissions'], function (Blueprint $table) {
            $table->uuid('id');
            $table->string('name');
            $table->string('guard_name');
            $table->string('display_name');
            $table->text('description')->nullable();
            $table->timestamps();

            $table->primary('id');
        });

        Schema::create($tableNames['roles'], function (Blueprint $table) {
            $table->uuid('id');
            $table->string('name');
            $table->string('guard_name');
            $table->string('display_name');
            $table->text('description')->nullable();
            $table->uuid('created_by_id')->nullable();
            $table->uuid('updated_by_id')->nullable();
            $table->timestamps();

            $table->primary('id');
            $table->foreign('created_by_id')
                ->references('id')
                ->on('users')
                ->onDelete('set null')
                ->onUpdate('cascade');
            $table->foreign('updated_by_id')
                ->references('id')
                ->on('users')
                ->onDelete('set null')
                ->onUpdate('cascade');
        });

        Schema::create($tableNames['model_has_permissions'], function (Blueprint $table) use ($tableNames, $columnNames) {
            $table->uuid('permission_id');
            $table->string('model_type');
            $table->uuid($columnNames['model_morph_key']);

            $table->primary([
                'permission_id',
                $columnNames['model_morph_key'],
                'model_type'
            ], 'model_permission_permission_model_type_primary');
            $table->foreign('permission_id')
                ->references('id')
                ->on($tableNames['permissions'])
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table->index([
                $columnNames['model_morph_key'], 'model_type'
            ], 'model_permission_model_id_model_type_index');
        });

        Schema::create($tableNames['model_has_roles'], function (Blueprint $table) use ($tableNames, $columnNames) {
            $table->uuid('role_id');
            $table->string('model_type');
            $table->uuid($columnNames['model_morph_key']);

            $table->primary([
                'role_id',
                $columnNames['model_morph_key'],
                'model_type'
            ], 'model_role_role_model_type_primary');
            $table->foreign('role_id')
                ->references('id')
                ->on($tableNames['roles'])
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table->index([
                $columnNames['model_morph_key'],
                'model_type'
            ], 'model_role_model_id_model_type_index');
        });

        Schema::create($tableNames['role_has_permissions'], function (Blueprint $table) use ($tableNames) {
            $table->uuid('permission_id');
            $table->uuid('role_id');

            $table->primary([
                'permission_id',
                'role_id'
            ], 'role_permission_permission_id_role_id_primary');
            $table->foreign('permission_id')
                ->references('id')
                ->on($tableNames['permissions'])
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->foreign('role_id')
                ->references('id')
                ->on($tableNames['roles'])
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });

        app('cache')
            ->store(config('permission.cache.store') != 'default' ? config('permission.cache.store') : null)
            ->forget(config('permission.cache.key'));           
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $tableNames = config('permission.table_names');

        Schema::drop($tableNames['role_has_permissions']);
        Schema::drop($tableNames['model_has_roles']);
        Schema::drop($tableNames['model_has_permissions']);
        Schema::drop($tableNames['roles']);
        Schema::drop($tableNames['permissions']);
    }
}

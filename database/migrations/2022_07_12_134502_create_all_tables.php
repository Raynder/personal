<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\PermissionRegistrar;

class CreateAllTables extends Migration
{
    public function up()
    {
        DB::raw("create extension if not exists \"uuid-ossp\";");

        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
        });

        Schema::create('password_resets', function (Blueprint $table) {
            $table->string('email')->index();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('failed_jobs', function (Blueprint $table) {
            $table->id();
            $table->string('uuid')->unique();
            $table->text('connection');
            $table->text('queue');
            $table->longText('payload');
            $table->longText('exception');
            $table->timestamp('failed_at')->useCurrent();
        });


        $tableNames = config('permission.table_names');
        $columnNames = config('permission.column_names');
        $teams = config('permission.teams');

        if (empty($tableNames)) {
            throw new \Exception('Error: config/permission.php not loaded. Run [php artisan config:clear] and try again.');
        }
        if ($teams && empty($columnNames['team_foreign_key'] ?? null)) {
            throw new \Exception('Error: team_foreign_key on config/permission.php not loaded. Run [php artisan config:clear] and try again.');
        }

        Schema::create($tableNames['permissions'], function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');       // Name like menu.menu_item.action
            $table->string('description'); // Description for Humans
            $table->string('guard_name');
            $table->unsignedInteger('order');
            $table->timestamps();

            $table->unique(['name', 'guard_name']);
        });

        Schema::create($tableNames['roles'], function (Blueprint $table) use ($teams, $columnNames) {
            $table->bigIncrements('id');
            if ($teams || config('permission.testing')) { // permission.testing is a fix for sqlite testing
                $table->unsignedBigInteger($columnNames['team_foreign_key'])->nullable();
                $table->index($columnNames['team_foreign_key'], 'roles_team_foreign_key_index');
            }
            $table->string('name');       // For MySQL 8.0 use string('name', 125);
            $table->string('guard_name'); // For MySQL 8.0 use string('guard_name', 125);
            $table->timestamps();
            if ($teams || config('permission.testing')) {
                $table->unique([$columnNames['team_foreign_key'], 'name', 'guard_name']);
            } else {
                $table->unique(['name', 'guard_name']);
            }
        });

        Schema::create($tableNames['model_has_permissions'], function (Blueprint $table) use ($tableNames, $columnNames, $teams) {
            $table->unsignedBigInteger(PermissionRegistrar::$pivotPermission);

            $table->string('model_type');
            $table->unsignedBigInteger($columnNames['model_morph_key']);
            $table->index([$columnNames['model_morph_key'], 'model_type'], 'model_has_permissions_model_id_model_type_index');

            $table->foreign(PermissionRegistrar::$pivotPermission)
                ->references('id')
                ->on($tableNames['permissions'])
                ->onDelete('cascade');
            if ($teams) {
                $table->unsignedBigInteger($columnNames['team_foreign_key']);
                $table->index($columnNames['team_foreign_key'], 'model_has_permissions_team_foreign_key_index');

                $table->primary(
                    [$columnNames['team_foreign_key'], PermissionRegistrar::$pivotPermission, $columnNames['model_morph_key'], 'model_type'],
                    'model_has_permissions_permission_model_type_primary'
                );
            } else {
                $table->primary(
                    [PermissionRegistrar::$pivotPermission, $columnNames['model_morph_key'], 'model_type'],
                    'model_has_permissions_permission_model_type_primary'
                );
            }
        });

        Schema::create($tableNames['model_has_roles'], function (Blueprint $table) use ($tableNames, $columnNames, $teams) {
            $table->unsignedBigInteger(PermissionRegistrar::$pivotRole);

            $table->string('model_type');
            $table->unsignedBigInteger($columnNames['model_morph_key']);
            $table->index([$columnNames['model_morph_key'], 'model_type'], 'model_has_roles_model_id_model_type_index');

            $table->foreign(PermissionRegistrar::$pivotRole)
                ->references('id')
                ->on($tableNames['roles'])
                ->onDelete('cascade');
            if ($teams) {
                $table->unsignedBigInteger($columnNames['team_foreign_key']);
                $table->index($columnNames['team_foreign_key'], 'model_has_roles_team_foreign_key_index');

                $table->primary(
                    [$columnNames['team_foreign_key'], PermissionRegistrar::$pivotRole, $columnNames['model_morph_key'], 'model_type'],
                    'model_has_roles_role_model_type_primary'
                );
            } else {
                $table->primary(
                    [PermissionRegistrar::$pivotRole, $columnNames['model_morph_key'], 'model_type'],
                    'model_has_roles_role_model_type_primary'
                );
            }
        });

        Schema::create($tableNames['role_has_permissions'], function (Blueprint $table) use ($tableNames) {
            $table->unsignedBigInteger(PermissionRegistrar::$pivotPermission);
            $table->unsignedBigInteger(PermissionRegistrar::$pivotRole);

            $table->foreign(PermissionRegistrar::$pivotPermission)
                ->references('id')
                ->on($tableNames['permissions'])
                ->onDelete('cascade');

            $table->foreign(PermissionRegistrar::$pivotRole)
                ->references('id')
                ->on($tableNames['roles'])
                ->onDelete('cascade');

            $table->primary([PermissionRegistrar::$pivotPermission, PermissionRegistrar::$pivotRole], 'role_has_permissions_permission_id_role_id_primary');
        });

        app('cache')
            ->store(config('permission.cache.store') != 'default' ? config('permission.cache.store') : null)
            ->forget(config('permission.cache.key'));


        Schema::create('empresas', function (Blueprint $table) {
            $table->id('id');
            $table->string('razao_social', 150);
            $table->string('fantasia', 150)->nullable();
            $table->string("email", 150);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('empresa_users', function (Blueprint $table) {
            $table->unsignedInteger('empresa_id');
            $table->unsignedInteger('user_id');

            $table->foreign('empresa_id')->references('id')->on('empresas');
            $table->foreign('user_id')->references('id')->on('users');

            $table->primary(['empresa_id', 'user_id']);
        });

        Schema::table('users', function (BluePrint $table) {
            $table->string('type', '1')->default('F');
            $table->foreignId('empresa_id')->nullable()->constrained('empresas');
        });

        Schema::create('alunos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('empresa_id')->constrained();
            $table->string('cnpj', 14)->nullable();
            $table->string('razao_social', 150)->nullable();
            $table->string('fantasia', 150)->nullable();
            $table->string('nome', 36);
            $table->string('senha', 36);
            $table->string('email', 150);
            $table->text('certificado')->nullable();
            $table->string('num_serie', 100)->nullable();
            $table->string('validade', 10)->nullable();
            $table->string('token', 50)->nullable();
            $table->date('validade_token')->nullable();
            $table->integer('criptografia')->default('0');
            $table->string('novasenha')->nullable()->after('senha');
            $table->unique(['empresa_id', 'cnpj']);
            $table->date('data_validade')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        //Relação com empresa
        Schema::create('exercicios', function (Blueprint $table) {
            $table->id();
            $table->string('nome',45);
            $table->string('uuid',45);

            $table->unsignedBigInteger('empresa_id')->nullable();
            $table->foreign('empresa_id')->references('id')->on('empresas');
            $table->timestamps();
        });

        //Relação com empresa
        Schema::create('treinos', function (Blueprint $table) {
            $table->id();
            $table->string('nome',20);

            $table->unsignedBigInteger('empresa_id')->nullable();
            $table->foreign('empresa_id')->references('id')->on('empresas')->onDelete('cascade');
            $table->timestamps();
        });

        //Relação com empresa
        Schema::create('exercicio_treino', function (Blueprint $table) {
            $table->id();
            $table->foreignId('exercicio_id')->constrained();
            $table->foreignId('treino_id')->constrained();

            $table->timestamps();
        });

        //Relação com treino e aluno
        Schema::create('treino_aluno', function (Blueprint $table) {
            $table->id();
            $table->foreignId('treino_id')->constrained();
            $table->foreignId('aluno_id')->constrained();
            $table->timestamps();
        });

        //Relação com empresa e exercicio
        Schema::create('acessos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('aluno_id')->nullable()->constrained();
            $table->string('chave', 44);
            $table->string('status', 2)->default('P')->comment('P = Pendente, A = Ativo, I = Inativo');
            $table->string('exercicio', 50)->nullable();
            $table->string('uuid_exercicio', 36)->nullable();
            $table->dateTime('data_limite')->nullable();

            $table->integer('empresa_id')->unsigned()->nullable();
            $table->foreign('empresa_id')->references('id')->on('empresas');

            $table->unsignedBigInteger('exercicio_id')->nullable();
            $table->foreign('exercicio_id')->references('id')->on('exercicios')->onDelete('cascade');
            $table->timestamps();
        });

        Schema::create('novidades', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('description');
            $table->integer('type')->comment('1: new version, 2: question, 3: information');
            $table->string('version')->comment('version of the update')->unique();
            $table->string('link')->nullable();
            $table->text('arquivo')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        $tableNames = config('permission.table_names');

        if (empty($tableNames)) {
            throw new \Exception('Error: config/permission.php not found and defaults could not be merged. Please publish the package configuration before proceeding, or drop the tables manually.');
        }
        Schema::dropIfExists($tableNames['role_has_permissions']);
        Schema::dropIfExists($tableNames['model_has_roles']);
        Schema::dropIfExists($tableNames['model_has_permissions']);
        Schema::dropIfExists($tableNames['roles']);
        Schema::dropIfExists($tableNames['permissions']);
        Schema::dropIfExists('pessoas');
        Schema::dropIfExists('failed_jobs');
        Schema::dropIfExists('password_resets');
        Schema::dropIfExists('acessos');
        Schema::dropIfExists('certificados');
        Schema::dropIfExists('empresa_users');
        Schema::dropIfExists('users');
        Schema::dropIfExists('empresas');

    }
}

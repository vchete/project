<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAllTablesAuth extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //AUTH
        Schema::create('auth_usuarios', function($table) {
            $table->increments('usuario_id');
            $table->string('codigo', 10)->index()->nullable()->unique();
            $table->string('email', 30)->index()->unique();
            $table->string('password', 100);
            $table->string('nombres', 30);
            $table->string('apellidos', 30);
            $table->boolean('cambiar_password')->unsigned()->default(true);
            $table->string('facebook_id', 100)->nullable();
            $table->string('google_id', 100)->nullable();
            $table->rememberToken();
            $table->boolean('activo')->unsigned()->default(true);
            $table->nullableTimestamps();
            $table->softDeletes();
        });
        Schema::create('auth_roles', function($table) {
            $table->increments('rol_id');
            $table->string('nombre', 20)->unique();
            $table->string('descripcion', 50)->nullable();
            $table->nullableTimestamps();
            $table->softDeletes();
        });
        Schema::create('auth_usuario_roles', function($table) {
            $table->increments('usuario_rol_id');
            $table->integer('usuario_id')->unsigned();
            $table->integer('rol_id')->unsigned();
            $table->unique(['usuario_id', 'rol_id']);
            $table->nullableTimestamps();
            $table->foreign('usuario_id')->references('usuario_id')->on('auth_usuarios')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('rol_id')->references('rol_id')->on('auth_roles')->onDelete('cascade')->onUpdate('cascade');
        });
        Schema::create('auth_modulos', function($table) {
            $table->increments('modulo_id');
            $table->string('nombre', 50)->unique();
            $table->string('nombre_friendly', 50);
            $table->boolean('mostrar')->unsigned()->default(true);
            $table->nullableTimestamps();
            $table->softDeletes();
        });
        Schema::create('auth_permisos', function($table) {
            $table->increments('permiso_id');
            $table->string('nombre', 20)->unique();
            $table->string('nombre_friendly', 20);
            $table->boolean('mostrar')->unsigned()->default(true);
            $table->nullableTimestamps();
            $table->softDeletes();
        });
        Schema::create('auth_modulo_permisos', function($table) {
            $table->increments('modulo_permiso_id');
            $table->integer('modulo_id')->unsigned();
            $table->integer('permiso_id')->unsigned();
            $table->unique(['modulo_id', 'permiso_id']);
            $table->nullableTimestamps();
            $table->foreign('modulo_id')->references('modulo_id')->on('auth_modulos')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('permiso_id')->references('permiso_id')->on('auth_permisos')->onDelete('cascade')->onUpdate('cascade');
        });
        Schema::create('auth_rol_modulo_permisos', function($table) {
            $table->increments('rol_modulo_permiso_id');
            $table->integer('rol_id')->unsigned();
            $table->integer('modulo_permiso_id')->unsigned();
            $table->unique(['rol_id', 'modulo_permiso_id']);
            $table->nullableTimestamps();
            $table->foreign('rol_id')->references('rol_id')->on('auth_roles')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('modulo_permiso_id')->references('modulo_permiso_id')->on('auth_modulo_permisos')->onDelete('cascade')->onUpdate('cascade');
        });
        Schema::create('auth_menu', function($table) {
            $table->increments('menu_id');
            $table->integer('padre_id')->unsigned()->nullable();
            $table->integer('modulo_permiso_id')->unsigned()->nullable();
            $table->string('nombre', 30);
            $table->integer('orden')->unsigned()->nullable();
            $table->string('icono', 30)->nullable();
            $table->nullableTimestamps();
            $table->foreign('padre_id')->references('menu_id')->on('auth_menu')->onUpdate('cascade');
            $table->foreign('modulo_permiso_id')->references('modulo_permiso_id')->on('auth_modulo_permisos')->onUpdate('cascade');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //AUTH
        Schema::dropIfExists('auth_menu');
        Schema::dropIfExists('auth_rol_modulo_permisos');
        Schema::dropIfExists('auth_modulo_permisos');
        Schema::dropIfExists('auth_permisos');
        Schema::dropIfExists('auth_modulos');
        Schema::dropIfExists('auth_usuario_roles');
        Schema::dropIfExists('auth_roles');
        Schema::dropIfExists('auth_usuarios');
    }
}

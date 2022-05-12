<?php namespace Chronos\App\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateChronosAppRoles extends Migration
{
    public function up()
    {
        Schema::create('chronos_app_roles', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id')->unsigned();
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
            $table->string('title');
            $table->text('desc')->nullable();
            $table->integer('role_id')->nullable();
            $table->integer('price')->nullable();
            $table->integer('sort_order')->nullable();
            $table->boolean('in_sale')->default(0);
            $table->boolean('is_final')->default(0);
            $table->integer('percent')->nullable();
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('chronos_app_roles');
    }
}

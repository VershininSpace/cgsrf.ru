<?php namespace Chronos\App\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateChronosAppRolesUsers extends Migration
{
    public function up()
    {
        Schema::create('chronos_app_roles_users', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id')->unsigned();
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
            $table->string('status')->nullable();
            $table->string('pay_url')->nullable();
            $table->boolean('accepted')->default(0);
            $table->dateTime('payment_at')->nullable();
            $table->boolean('paid')->default(0);
            $table->text('payment_id')->nullable();
            $table->integer('user_id');
            $table->integer('role_id');
            $table->integer('price')->nullable();
            $table->string('franch_email')->nullable();
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('chronos_app_roles_users');
    }
}

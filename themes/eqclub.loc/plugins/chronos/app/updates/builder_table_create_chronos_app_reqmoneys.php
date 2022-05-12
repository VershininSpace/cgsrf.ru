<?php namespace Chronos\App\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateChronosAppReqmoneys extends Migration
{
    public function up()
    {
        Schema::create('chronos_app_reqmoneys', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id')->unsigned();
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
            $table->integer('user_id');
            $table->string('total');
            $table->string('status')->default('Новый');
            $table->boolean('is_partial')->default(0);
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('chronos_app_reqmoneys');
    }
}

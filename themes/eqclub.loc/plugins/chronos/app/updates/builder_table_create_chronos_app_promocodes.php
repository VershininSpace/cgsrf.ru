<?php namespace Chronos\App\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateChronosAppPromocodes extends Migration
{
    public function up()
    {
        Schema::create('chronos_app_promocodes', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
            $table->string('code');
            $table->string('amount');
            $table->boolean('is_active')->default(1);
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('chronos_app_promocodes');
    }
}

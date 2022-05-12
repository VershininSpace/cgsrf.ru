<?php namespace Chronos\App\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateChronosAppTransactions extends Migration
{
    public function up()
    {
        Schema::create('chronos_app_transactions', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('user_id');
            $table->integer('product_id');
            $table->integer('total');
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('chronos_app_transactions');
    }
}

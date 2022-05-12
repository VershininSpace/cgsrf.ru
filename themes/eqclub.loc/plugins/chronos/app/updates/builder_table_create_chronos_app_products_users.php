<?php namespace Chronos\App\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateChronosAppProductsUsers extends Migration
{
    public function up()
    {
        Schema::create('chronos_app_products_users', function($table)
        {
            $table->engine = 'InnoDB';
            $table->integer('product_id');
            $table->integer('user_id');
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('chronos_app_products_users');
    }
}

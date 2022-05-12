<?php namespace Chronos\App\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateChronosAppProductsUsers extends Migration
{
    public function up()
    {
        Schema::table('chronos_app_products_users', function($table)
        {
            $table->string('status')->default('проверка');
        });
    }
    
    public function down()
    {
        Schema::table('chronos_app_products_users', function($table)
        {
            $table->dropColumn('status');
        });
    }
}

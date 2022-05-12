<?php namespace Chronos\App\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateChronosAppProductsUsers6 extends Migration
{
    public function up()
    {
        Schema::table('chronos_app_products_users', function($table)
        {
            $table->string('status', 191)->default('WAITING')->change();
        });
    }
    
    public function down()
    {
        Schema::table('chronos_app_products_users', function($table)
        {
            $table->string('status', 191)->default('waiting')->change();
        });
    }
}

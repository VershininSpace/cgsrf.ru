<?php namespace Chronos\App\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateChronosAppProducts5 extends Migration
{
    public function up()
    {
        Schema::table('chronos_app_products', function($table)
        {
            $table->integer('percent')->nullable();
            $table->string('reg_link')->nullable();
        });
    }
    
    public function down()
    {
        Schema::table('chronos_app_products', function($table)
        {
            $table->dropColumn('percent');
            $table->dropColumn('reg_link');
        });
    }
}

<?php namespace Chronos\App\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateChronosAppProducts9 extends Migration
{
    public function up()
    {
        Schema::table('chronos_app_products', function($table)
        {
            $table->string('link')->nullable();
        });
    }
    
    public function down()
    {
        Schema::table('chronos_app_products', function($table)
        {
            $table->dropColumn('link');
        });
    }
}

<?php namespace Chronos\App\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateChronosAppReqmoneys extends Migration
{
    public function up()
    {
        Schema::table('chronos_app_reqmoneys', function($table)
        {
            $table->boolean('is_closed')->default(0);
        });
    }
    
    public function down()
    {
        Schema::table('chronos_app_reqmoneys', function($table)
        {
            $table->dropColumn('is_closed');
        });
    }
}

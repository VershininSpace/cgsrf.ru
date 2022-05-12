<?php namespace Chronos\App\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateChronosAppPromocodes extends Migration
{
    public function up()
    {
        Schema::table('chronos_app_promocodes', function($table)
        {
            $table->boolean('is_secret')->default(0);
        });
    }
    
    public function down()
    {
        Schema::table('chronos_app_promocodes', function($table)
        {
            $table->dropColumn('is_secret');
        });
    }
}

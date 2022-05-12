<?php namespace Chronos\App\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateChronosAppTransactions extends Migration
{
    public function up()
    {
        Schema::table('chronos_app_transactions', function($table)
        {
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
        });
    }
    
    public function down()
    {
        Schema::table('chronos_app_transactions', function($table)
        {
            $table->dropColumn('created_at');
            $table->dropColumn('updated_at');
        });
    }
}

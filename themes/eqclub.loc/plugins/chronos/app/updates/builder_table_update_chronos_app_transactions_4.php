<?php namespace Chronos\App\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateChronosAppTransactions4 extends Migration
{
    public function up()
    {
        Schema::table('chronos_app_transactions', function($table)
        {
            $table->dateTime('payment_at')->nullable();
            $table->boolean('paid')->default(0);
        });
    }
    
    public function down()
    {
        Schema::table('chronos_app_transactions', function($table)
        {
            $table->dropColumn('payment_at');
            $table->dropColumn('paid');
        });
    }
}

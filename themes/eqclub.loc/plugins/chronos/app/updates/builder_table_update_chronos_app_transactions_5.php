<?php namespace Chronos\App\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateChronosAppTransactions5 extends Migration
{
    public function up()
    {
        Schema::table('chronos_app_transactions', function($table)
        {
            $table->dropColumn('payment_id');
            $table->dropColumn('payment_at');
            $table->dropColumn('paid');
        });
    }
    
    public function down()
    {
        Schema::table('chronos_app_transactions', function($table)
        {
            $table->string('payment_id', 191)->nullable();
            $table->dateTime('payment_at')->nullable();
            $table->boolean('paid')->default(0);
        });
    }
}

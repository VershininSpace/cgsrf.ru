<?php namespace Chronos\App\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateChronosAppTransactions9 extends Migration
{
    public function up()
    {
        Schema::table('chronos_app_transactions', function($table)
        {
            $table->integer('product_id')->nullable()->change();
        });
    }
    
    public function down()
    {
        Schema::table('chronos_app_transactions', function($table)
        {
            $table->integer('product_id')->nullable(false)->change();
        });
    }
}

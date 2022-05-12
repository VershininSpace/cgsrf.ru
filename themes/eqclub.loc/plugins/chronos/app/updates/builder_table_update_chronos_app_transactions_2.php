<?php namespace Chronos\App\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateChronosAppTransactions2 extends Migration
{
    public function up()
    {
        Schema::table('chronos_app_transactions', function($table)
        {
            $table->integer('from_user')->nullable();
            $table->decimal('total', 10, 0)->nullable(false)->unsigned(false)->default(null)->change();
        });
    }
    
    public function down()
    {
        Schema::table('chronos_app_transactions', function($table)
        {
            $table->dropColumn('from_user');
            $table->integer('total')->nullable(false)->unsigned(false)->default(null)->change();
        });
    }
}

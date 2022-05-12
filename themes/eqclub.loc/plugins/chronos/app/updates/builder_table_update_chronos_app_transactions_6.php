<?php namespace Chronos\App\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateChronosAppTransactions6 extends Migration
{
    public function up()
    {
        Schema::table('chronos_app_transactions', function($table)
        {
            $table->integer('level')->nullable();
            $table->integer('buyer')->nullable();
            $table->boolean('enroll')->default(1);
            $table->string('product_type');
        });
    }
    
    public function down()
    {
        Schema::table('chronos_app_transactions', function($table)
        {
            $table->dropColumn('level');
            $table->dropColumn('buyer');
            $table->dropColumn('enroll');
            $table->dropColumn('product_type');
        });
    }
}

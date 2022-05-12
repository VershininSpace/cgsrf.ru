<?php namespace Chronos\App\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateChronosAppProductsUsers3 extends Migration
{
    public function up()
    {
        Schema::table('chronos_app_products_users', function($table)
        {
            $table->string('payment_id')->nullable();
            $table->dateTime('payment_at')->nullable();
            $table->boolean('paid')->default(0);
        });
    }
    
    public function down()
    {
        Schema::table('chronos_app_products_users', function($table)
        {
            $table->dropColumn('payment_id');
            $table->dropColumn('payment_at');
            $table->dropColumn('paid');
        });
    }
}

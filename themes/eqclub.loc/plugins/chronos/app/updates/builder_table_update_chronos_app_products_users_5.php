<?php namespace Chronos\App\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateChronosAppProductsUsers5 extends Migration
{
    public function up()
    {
        Schema::table('chronos_app_products_users', function($table)
        {
            $table->integer('price')->nullable();
            $table->string('title')->nullable();
            $table->text('pay_url')->nullable();
            $table->boolean('accepted')->default(0);
            $table->string('reg_link')->nullable();
        });
    }
    
    public function down()
    {
        Schema::table('chronos_app_products_users', function($table)
        {
            $table->dropColumn('price');
            $table->dropColumn('title');
            $table->dropColumn('pay_url');
            $table->dropColumn('accepted');
            $table->dropColumn('reg_link');
        });
    }
}

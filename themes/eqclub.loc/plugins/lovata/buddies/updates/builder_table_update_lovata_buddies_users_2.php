<?php namespace Lovata\Buddies\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateLovataBuddiesUsers2 extends Migration
{
    public function up()
    {
        Schema::table('lovata_buddies_users', function($table)
        {
            $table->decimal('balance', 10, 0)->default(0);
        });
    }
    
    public function down()
    {
        Schema::table('lovata_buddies_users', function($table)
        {
            $table->dropColumn('balance');
        });
    }
}

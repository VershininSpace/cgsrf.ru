<?php namespace Lovata\Buddies\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateLovataBuddiesUsers extends Migration
{
    public function up()
    {
        Schema::table('lovata_buddies_users', function($table)
        {
            $table->integer('parent_id')->nullable();
        });
    }
    
    public function down()
    {
        Schema::table('lovata_buddies_users', function($table)
        {
            $table->dropColumn('parent_id');
        });
    }
}

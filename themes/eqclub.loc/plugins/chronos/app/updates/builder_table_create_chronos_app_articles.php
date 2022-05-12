<?php namespace Chronos\App\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateChronosAppArticles extends Migration
{
    public function up()
    {
        Schema::create('chronos_app_articles', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id')->unsigned();
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
            $table->string('title');
            $table->string('intro')->nullable();
            $table->text('text')->nullable();
            $table->string('description')->nullable();
            $table->string('slug')->nullable();
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('chronos_app_articles');
    }
}

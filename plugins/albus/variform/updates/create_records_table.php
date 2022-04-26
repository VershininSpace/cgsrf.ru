<?php namespace Albus\VariForm\Updates;

use Schema;
use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;

/**
 * CreateRecordsTable Migration
 */
class CreateRecordsTable extends Migration
{
    public function up()
    {
        Schema::create('albus_variform_records', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            
            $table->text('form_data')->nullable();
            $table->boolean('unread')->default(1);
            $table->string('ip')->nullable();
            $table->string('group')->nullable();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('albus_variform_records');
    }
}

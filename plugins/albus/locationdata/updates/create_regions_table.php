<?php namespace Albus\LocationData\Updates;

use Schema;
use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;

/**
 * CreateRegionsTable Migration
 */
class CreateRegionsTable extends Migration
{
    public function up()
    {
        Schema::create('albus_locationdata_regions', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();

            $table->string('name');
            $table->string('slug');
            $table->string('code')->nullable();
            $table->double('number_code')->nullable();
            
            $table->text('content')->nullable();
            $table->text('phones')->nullable();
            $table->string('address')->nullable();
            $table->string('coordinates')->nullable();

            $table->integer('sort_order')->nullable();

        });
    }

    public function down()
    {
        Schema::dropIfExists('albus_locationdata_regions');
    }
}

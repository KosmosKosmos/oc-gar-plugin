<?php namespace KosmosKosmos\GAR2\Updates;

use Schema;
use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;

class CreateConfirmsTable extends Migration
{
    public function up()
    {
        Schema::create('kosmoskosmos_gar_confirms', function(Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->boolean('confirmed')->default(false);
            $table->integer('confirmable_id')->unsigned();
            $table->string('confirmable_type');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('kosmoskosmos_gar_confirms');
    }
}

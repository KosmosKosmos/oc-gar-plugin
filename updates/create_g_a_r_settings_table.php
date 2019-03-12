<?php namespace KosmosKosmos\GAR\Updates;

use Schema;
use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;

class CreateGARSettingsTable extends Migration
{
    public function up()
    {
        Schema::create('kosmoskosmos_gar_g_a_r_settings', function(Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('kosmoskosmos_gar_g_a_r_settings');
    }
}

<?php namespace KosmosKosmos\GAR2\Updates;

use Schema;
use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;

class CreateRoleInfosTable extends Migration
{
    public function up()
    {
        Schema::create('kosmoskosmos_gar_role_infos', function(Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('role_id')->unsigned();
            $table->string('company');
            $table->string('street');
            $table->string('zip');
            $table->string('city');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('kosmoskosmos_gar_role_infos');
    }
}

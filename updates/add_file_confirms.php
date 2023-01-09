<?php namespace KosmosKosmos\GAR2\Updates;

use Schema;
use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;

class AddFileConfirms extends Migration
{
    public function up()
    {
        Schema::table('kosmoskosmos_gar_confirms', function(Blueprint $table) {
            $table->string('file');
        });
    }

    public function down()
    {
        Schema::table('kosmoskosmos_gar_confirms', function(Blueprint $table) {
			if (Schema::hasColumn('kosmoskosmos_gar_confirms', 'file')) {
				$table->dropColumn('file');
			}
        });
    }
}

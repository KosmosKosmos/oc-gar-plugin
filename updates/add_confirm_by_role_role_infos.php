<?php namespace KosmosKosmos\GAR2\Updates;

use Schema;
use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;

class AddConfirmByRoleRoleInfos extends Migration
{
    public function up()
    {
        Schema::table('kosmoskosmos_gar_role_infos', function(Blueprint $table) {
            $table->boolean('confirm_by_role')->default(true);
        });
    }

    public function down()
    {
        Schema::table('kosmoskosmos_gar_role_infos', function(Blueprint $table) {
			if (Schema::hasColumn('kosmoskosmos_gar_role_infos', 'confirm_by_role')) {
				$table->dropColumn('confirm_by_role');
			}
        });
    }
}

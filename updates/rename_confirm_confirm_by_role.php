<?php namespace KosmosKosmos\GAR\Updates;

use Schema;
use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;

/**
 * Renames Database columns in correspondance with October CMS Marketplace specifications.
 *
 * Class RenameConfirmConfirmByRole
 * @package KosmosKosmos\GAR\Updates
 */
class RenameConfirmConfirmByRole extends Migration
{
    public function up()
    {
        Schema::table('kosmoskosmos_gar_role_infos', function(Blueprint $table) {
            $table->renameColumn('confirm_by_role', 'is_confirmed_by_role');
        });

        Schema::table('kosmoskosmos_gar_confirms', function(Blueprint $table) {
            $table->renameColumn('confirmed', 'is_confirmed');
        });
    }

    public function down()
    {
        Schema::table('kosmoskosmos_gar_role_infos', function(Blueprint $table) {
			if (Schema::hasColumn('kosmoskosmos_gar_role_infos', 'is_confirmed_by_role')) {
				$table->renameColumn('is_confirmed_by_role', 'confirm_by_role');
			}
        });

        Schema::table('kosmoskosmos_gar_confirms', function(Blueprint $table) {
            if (Schema::hasColumn('kosmoskosmos_gar_confirms', 'is_confirmed')) {
                $table->renameColumn('is_confirmed', 'confirmed');
            }

        });
    }
}

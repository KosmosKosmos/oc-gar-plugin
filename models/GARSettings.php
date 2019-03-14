<?php namespace KosmosKosmos\GAR\Models;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Log;
use Model;

/**
 * GARSettings Model
 */
class GARSettings extends Model
{
    public $implement = ['System.Behaviors.SettingsModel', 'RainLab.Translate.Behaviors.TranslatableModel'];
    public $translatable = [
        'gar_confirm_label',
        'gar_confirm_comment'
    ];
    /**
     * @var string The database table used by the model.
     */
    public $settingsCode = 'kosmoskosmos_gar_g_a_r_settings';
    public $settingsFields = 'fields.yaml';
    public $table = 'kosmoskosmos_gar_g_a_r_settings';

    /**
     * @var array Guarded fields
     */
    protected $guarded = ['*'];

    /**
     * @var array Fillable fields
     */
    protected $fillable = [];

    /**
     * @var array Relations
     */
    public $hasOne = [];
    public $hasMany = [];
    public $belongsTo = [];
    public $belongsToMany = [];
    public $morphTo = [];
    public $morphOne = [];
    public $morphMany = [];
    public $attachOne = [];
    public $attachMany = [];

    public function beforeSave() {
        if (!File::exists(storage_path('kosmoskosmos'))) {
            File::makeDirectory(storage_path('kosmoskosmos'));
        }

        File::put(storage_path('kosmoskosmos/gar.html'), Input::all()['GARSettings']['gar_text']);
        unset(Input::all()['GARSettings']['gar_text']);
    }

    public function afterFetch() {
        if (File::exists(storage_path('kosmoskosmos/gar.html'))) {
            $this->gat_text = File::get(storage_path('kosmoskosmos/gar.html'));
        }

    }
}

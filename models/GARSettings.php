<?php namespace KosmosKosmos\GAR\Models;

use Illuminate\Support\Facades\File;
use Model;

/**
 * GARSettings Model
 */
class GARSettings extends Model
{
    public $implement = ['System.Behaviors.SettingsModel', 'RainLab.Translate.Behaviors.TranslatableModel'];
    public $translatable = [
        
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

    public function afterFetch() {
        if (File::exists(storage_path('kosmoskosmos/gar.html'))) {
            $this->gar_text = File::get(storage_path('kosmoskosmos/gar.html'));
        }
    }

    public function beforeSave() {
        if (!File::exists(storage_path('kosmoskosmos'))) {
            File::makeDirectory(storage_path('kosmoskosmos'));
        }

        File::put(storage_path('kosmoskosmos/gar.html'), $this->gar_text);
        unset($this->gar_text);
    }
}

<?php namespace KosmosKosmos\GAR2\Models;

use Model;

/**
 * RoleInfo Model
 */
class RoleInfo extends Model
{
    /**
     * @var string The database table used by the model.
     */
    public $table = 'kosmoskosmos_gar_role_infos';

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
    public $belongsTo = [
        'role' => [
            'Backend\Models\UserRole',
            'key' => 'role_id',
        ],
    ];
    public $belongsToMany = [];
    public $morphTo = [];
    public $morphOne = [];
    public $morphMany = [];
    public $attachOne = [];
    public $attachMany = [];
}

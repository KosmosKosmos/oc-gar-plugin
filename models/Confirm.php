<?php namespace KosmosKosmos\GAR\Models;

use Model;

/**
 * Confirm Model
 */
class Confirm extends Model
{
    /**
     * @var string The database table used by the model.
     */
    public $table = 'kosmoskosmos_gar_confirms';

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
    public $morphTo = [
        'imageable' => []
    ];
    public $morphOne = [];
    public $morphMany = [];
    public $attachOne = [];
    public $attachMany = [];
}

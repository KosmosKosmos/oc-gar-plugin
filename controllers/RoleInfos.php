<?php namespace KosmosKosmos\GAR2\Controllers;

use BackendMenu;
use Backend\Classes\Controller;

/**
 * Role Infos Back-end Controller
 */
class RoleInfos extends Controller
{
    public $implement = [
        'Backend.Behaviors.FormController',
        'Backend.Behaviors.ListController'
    ];

    public $formConfig = 'config_form.yaml';
    public $listConfig = 'config_list.yaml';

    public function __construct()
    {
        parent::__construct();

        BackendMenu::setContext('KosmosKosmos.GAR', 'gar', 'roleinfos');
    }
}

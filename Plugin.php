<?php namespace KosmosKosmos\GAR;

use Backend;
use Backend\Classes\Controller;
use Backend\Facades\BackendAuth;
use Flynsarmy\Mfa\Classes\BackendAuthManager;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use KosmosKosmos\GAR\Models\RoleInfo;
use System\Classes\PluginBase;

/**
 * GAR Plugin Information File
 */
class Plugin extends PluginBase
{
    public $require = ['Renatio.DynamicPDF', 'Rainlab.Translate'];
    /**
     * Returns information about this plugin.
     *
     * @return array
     */
    public function pluginDetails()
    {
        return [
            'name'        => 'kosmoskosmos.gar::lang.gar',
            'description' => 'kosmoskosmos.gar::lang.description',
            'author'      => 'KosmosKosmos',
            'icon'        => 'icon-certificate'
        ];
    }

    /**
     * Register method, called when the plugin is first registered.
     *
     * @return void
     */
    public function register()
    {
        $this->registerConsoleCommand('gar.deleteconfirm', 'KosmosKosmos\GAR\Console\DeleteConfirm');
    }

    /**
     * Boot method, called right before the request route.
     *
     * @return array
     */
    public function boot()
    {
        Backend\Models\User::extend(function ($model) {
            $model->morphOne['gar_confirm'] = [
                'KosmosKosmos\GAR\Models\Confirm',
                'name' => 'confirmable'
            ];
        });
        Backend\Models\UserRole::extend(function ($model) {
            $model->morphOne['gar_confirm'] = [
                'KosmosKosmos\GAR\Models\Confirm',
                'name' => 'confirmable'
            ];
        });

        Event::listen('backend.page.beforeDisplay', function(Controller $controller, $action) {
            if ( !BackendAuth::check() || in_array($action, $controller->getPublicActions()) )
                return;

            $user = BackendAuth::getUser();
            $role = $user->role;
            $roleInfo = RoleInfo::where('role_id', '=', $role->id)->first();
            if ($roleInfo) {
                if (($roleInfo->is_confirmed_by_role && !$role->gar_confirm) ||
                        (!$roleInfo->is_confirmed_by_role && !$user->gar_confirm)
                ) {
                    return Request::ajax()
                        ? Response::make(trans('backend::lang.page.access_denied.label'), 403)
                        : Redirect::guest(Backend::url('kosmoskosmos/gar/confirms/confirm'));

                }
            }
        });
    }

    /**
     * Registers any front-end components implemented in this plugin.
     *
     * @return array
     */
    public function registerComponents()
    {
        return []; // Remove this line to activate

        return [
            'KosmosKosmos\GAR\Components\MyComponent' => 'myComponent',
        ];
    }

    /**
     * Registers any back-end permissions used by this plugin.
     *
     * @return array
     */
    public function registerPermissions()
    {
        return [
            'kosmoskosmos.gar.manage_gar' => [
                'tab' => 'GAR',
                'label' => 'Manage GAR'
            ],
        ];
    }

    /**
     * Registers back-end navigation items for this plugin.
     *
     * @return array
     */
    public function registerNavigation()
    {
        return [
            'gar' => [
                'label'       => 'kosmoskosmos.gar::lang.gar',
                'url'         => Backend::url('kosmoskosmos/gar/roleinfos'),
                'icon'        => 'icon-certificate',
                'permissions' => ['kosmoskosmos.gar.manage_gar'],
                'order'       => 500,
                'sideMenu'    => [
                    'role_infos' => [
                        'label' => 'kosmoskosmos.gar::lang.role_infos.role_infos',
                        'icon' => 'icon-info',
                        'url' => Backend::url('kosmoskosmos/gar/roleinfos'),
                        'permissions' => ['kosmoskosmos.gar.manage_gar'],
                    ],
                    'confirms' => [
                        'label' => 'kosmoskosmos.gar::lang.confirms.confirms',
                        'icon' => 'icon-check',
                        'url' => Backend::url('kosmoskosmos/gar/confirms'),
                        'permissions' => ['kosmoskosmos.gar.manage_gar'],
                    ],
                ]
            ],

        ];
    }

    public function registerSettings() {
        return [
            'gar_settings' => [
                'label' => 'kosmoskosmos.gar::lang.settings.gar_settings',
                'description' => 'kosmoskosmos.gar::lang.settings.comment',
                'category' => 'GAR',
                'icon' => 'icon-certificate',
                'class' => 'KosmosKosmos\GAR\Models\GARSettings',
                'order' => 500,
                'keywords' => 'gar confirmation avv bestätigung bestaetigung',
                'permissions' => ['kosmoskosmos.gar.manage_gar']
            ]
        ];
    }
}

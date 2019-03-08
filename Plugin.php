<?php namespace KosmosKosmos\GAR;

use Backend;
use System\Classes\PluginBase;

/**
 * GAR Plugin Information File
 */
class Plugin extends PluginBase
{
    /**
     * Returns information about this plugin.
     *
     * @return array
     */
    public function pluginDetails()
    {
        return [
            'name'        => 'GAR',
            'description' => 'No description provided yet...',
            'author'      => 'KosmosKosmos',
            'icon'        => 'icon-leaf'
        ];
    }

    /**
     * Register method, called when the plugin is first registered.
     *
     * @return void
     */
    public function register()
    {

    }

    /**
     * Boot method, called right before the request route.
     *
     * @return array
     */
    public function boot()
    {

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
        return []; // Remove this line to activate

        return [
            'kosmoskosmos.gar.some_permission' => [
                'tab' => 'GAR',
                'label' => 'Some permission'
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
        return []; // Remove this line to activate

        return [
            'gar' => [
                'label'       => 'GAR',
                'url'         => Backend::url('kosmoskosmos/gar/mycontroller'),
                'icon'        => 'icon-leaf',
                'permissions' => ['kosmoskosmos.gar.*'],
                'order'       => 500,
            ],
        ];
    }
}

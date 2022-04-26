<?php namespace Albus\VariForm;

use Backend;
use System\Classes\PluginBase;

/**
 * VariForm Plugin Information File
 */
class Plugin extends PluginBase
{
    public function pluginDetails()
    {
        return [
            'name'        => 'VariForm',
            'description' => 'No description provided yet...',
            'author'      => 'Albus',
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
     * @return void
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
        return [
            'Albus\VariForm\Components\Form' => 'Form',
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
            'albus.variform.some_permission' => [
                'tab' => 'VariForm',
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
        return [
            'variform' => [
                'label'       => 'VariForm',
                'url'         => Backend::url('albus/variform/records'),
                'icon'        => 'icon-leaf',
                'permissions' => ['albus.variform.*'],
                'order'       => 500,
            ],
        ];
    }
}

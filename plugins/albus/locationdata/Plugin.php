<?php namespace Albus\LocationData;

use Cookie;
use Http;
use Event;
use Backend;
use Session;
use System\Classes\PluginBase;
use Albus\LocationData\Models\Region;
/**
 * LocationData Plugin Information File
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
            'name'        => 'LocationData',
            'description' => 'No description provided yet...',
            'author'      => 'Albus',
            'icon'        => 'icon-leaf'
        ];
    }

    public function register()
    {

    }

    public function boot()
    {

        Event::listen('cms.page.init', function($controller, $page) {
            if (!Session::get('Region')) {
                $json = Http::get('http://ip-api.com/json/' . '5.3.236.204' );
                $data = json_decode($json);
                Session::put("Region", $data->region);
            }
        }, 100);
    }

    public function registerSettings()
    {
        return [
            'settings' => [
                'label'       => 'Настройки регионов',
                'description' => 'rainlab.location::lang.settings.menu_description',
                'category'    => 'Albus',
                'icon'        => 'icon-map-signs',
                'class'       => 'Albus\LocationData\Models\Setting',
            ]
        ];
    }


    public function registerComponents()
    {
        return [
            'Albus\LocationData\Components\LocationPicker' => 'LocationPicker',
        ];
    }

    public function registerPermissions()
    {
        return []; // Remove this line to activate

        return [
            'albus.locationdata.some_permission' => [
                'tab' => 'LocationData',
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
            'regions' => [
                'label'       => 'Регионы',
                'url'         => Backend::url('albus/locationdata/regions'),
                'icon'        => 'icon-leaf',
                'permissions' => ['albus.locationdata.*'],
                'order'       => 500,
            ],
        ];
    }

    public function registerMarkupTags()
    {
        return [
            'filters' => [
                // A global function, i.e str_plural()
                'region' => [$this, 'region']

            ],
            'functions' => [

                // Using an inline closure
                'helloWorld' => function() { return 'Hello World!'; }
            ]
        ];
    }

    public function region($item)
    {
        $current = Session::get("Region",);
        $x = Region::where('code', Session::get('Region'))->first()->$item;
        return $x;
    }
}

<?php namespace Albus\LocationData\Components;

use Albus\LocationData\Models\Setting;
use Input;
use Session;
use Cms\Classes\ComponentBase;
use Albus\LocationData\Models\Region;


/**
 * LocationPicker Component
 */
class LocationPicker extends ComponentBase
{
    public function componentDetails()
    {
        return [
            'name' => 'LocationPicker',
            'description' => 'Управляет выбором региона на сайте'
        ];
    }

    public function defineProperties()
    {

    }

    public $regions;
    public $activeRegion;

    public function onSwitchRegion() {

        $region = Input::get('region');
        Session::put("Region", $region);

    }

    public function onRun() {
        if (Session::get('Region')) {
            $this->page['activeRegion'] = $this->activeRegion = Region::where('code', Session::get('Region'))->first();
        } else {
            $this->page['activeRegion'] = Region::getDefault();
        }

        $this->regions = $this->page['regions'] = $regions = Region::orderBy('name', 'asc')->lists('name', 'code');

        $this->defaultRegion = $this->page['defaultRegion'] = Region::where('id', Setting::get('default_region'))->first();
    }
}

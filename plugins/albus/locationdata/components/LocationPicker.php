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
            'name' => 'LocationPicker Component',
            'description' => 'No description provided yet...'
        ];
    }

    public function defineProperties()
    {
        return [
            'country' => [
                'title'       => 'Страна',
                'description' => 'Always prefix the URL with a language code.',
                'type'        => 'dropdown'
            ],
        ];
    }
    // public function getCountryOptions() {
    //     return Country::isEnabled()->orderBy('name', 'asc')->lists('name', 'id');
    // }

    public $regions;
    public $activeRegion;

    // public function onRun() {
    //     if (Session::get('Region')) {
    //         $this->page['activeRegion'] = $this->activeRegion = State::where('code', Session::get('Region'))->first();
    //     }
    //     $this->regions = $this->page['regions'] = $regions = State::whereCountryId($this->property('country'))->isEnabled()->orderBy('name', 'asc')->lists('name', 'code');
    // }

    public function onSwitchRegion() {
        $region = Input::get('region');
        Session::put("Region", $region);
    }
    public function onRun() {
        if (Session::get('Region')) {
            $this->page['activeRegion'] = $this->activeRegion = Region::where('code', Session::get('Region'))->first();
        }
        $this->regions = $this->page['regions'] = $regions = Region::orderBy('name', 'asc')->lists('name', 'code');

        $this->defaultRegion = $this->page['defaultRegion'] = Region::where('id', Setting::get('default_region'))->first();
    }
}

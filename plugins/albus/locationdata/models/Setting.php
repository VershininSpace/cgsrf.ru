<?php namespace Albus\LocationData\Models;

use Model;

class Setting extends Model
{
    public $implement = ['System.Behaviors.SettingsModel'];

    public $settingsCode = 'location_settings';
    public $settingsFields = 'fields.yaml';

    public function initSettingsData()
    {
        $this->google_maps_key = '';
        $this->default_region = 1;
    }

    public function getDefaultRegionOptions()
    {
        return Region::lists('name', 'id');
    }
}

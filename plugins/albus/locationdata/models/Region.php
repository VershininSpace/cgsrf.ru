<?php namespace Albus\LocationData\Models;

use Model;
use Albus\LocationData\Models\Setting;
/**
 * Region Model
 */
class Region extends Model
{
    use \October\Rain\Database\Traits\Validation;
    use \October\Rain\Database\Traits\Sortable;
    use \October\Rain\Database\Traits\Sluggable;

    public $table = 'albus_locationdata_regions';
    public $rules = [];
    protected $dates = [ 'created_at', 'updated_at' ];
    
    protected $slugs = ['slug' => 'name'];
    protected $jsonable = ['content', 'phones'];

    public $attachOne = [
        'logo' => 'System\Models\File'
    ];

    public function getDefault($attribute = null) {
        $default = (object)[$attribute => null ];
        $item = self::whereId(Setting::get('default_region'))->first();
        if(!$item) {
            $item = $default;
        }
        return $attribute === null ? $item : $item->$attribute;
    }
}

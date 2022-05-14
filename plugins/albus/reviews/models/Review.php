<?php namespace Albus\Reviews\Models;

use Model;

/**
 * Model
 */
class Review extends Model
{
    use \October\Rain\Database\Traits\Validation;
    use \October\Rain\Database\Traits\Sortable;

    /**
     * @var string The database table used by the model.
     */
    public $table = 'albus_reviews_reviews';

    public $rules = [ ];

    public $attachOne = [
        'photo'      => 'System\Models\File',
    ];
    public $attachMany = [
        'gallery'      => 'System\Models\File',
    ];


    /**
     * Scope new messages only
     */
    public function scopeIsNew($query)
    {
        return $query->where('unread', '=', 1);
    }

    /**
     * Scope read messages only
     */
    public function scopeIsRead($query)
    {
        return $query->where('unread', '=', 0);
    }


}

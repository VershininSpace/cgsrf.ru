<?php namespace Chronos\App\Models;

use Carbon\Carbon;
use Model;

/**
 * Model
 */
class Comment extends Model
{
    use \October\Rain\Database\Traits\Validation;
    

    /**
     * @var string The database table used by the model.
     */
    public $table = 'chronos_app_comments';

    /**
     * @var array Validation rules
     */
    public $rules = [
    ];

	public $belongsTo = [
		'article' => ['Chronos\App\Models\Article']
	];

	public function getCreatedAtAttribute($date)
	{
		return Carbon::createFromFormat('Y-m-d H:i:s', $date)->format('d-m-Y H:i');
	}
}

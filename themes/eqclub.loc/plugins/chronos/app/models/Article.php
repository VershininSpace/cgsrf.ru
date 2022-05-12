<?php namespace Chronos\App\Models;

use Carbon\Carbon;
use Model;

/**
 * Model
 */
class Article extends Model
{
	use \October\Rain\Database\Traits\Validation;
	use \October\Rain\Database\Traits\Sluggable;

	protected $slugs = ['slug' => 'title'];

	public $attachOne = [
		'image' => 'System\Models\File'
	];

	/**
	 * @var string The database table used by the model.
	 */
	public $table = 'chronos_app_articles';

	/**
	 * @var array Validation rules
	 */
	public $rules = [
	];

	public $hasMany = [
		'comments' => ['Chronos\App\Models\Comment']
	];

	public function getCreatedAtAttribute($date)
	{
		return Carbon::createFromFormat('Y-m-d H:i:s', $date)->format('d-m-Y H:i');
	}
}

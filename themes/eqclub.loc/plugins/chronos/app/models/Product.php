<?php namespace Chronos\App\Models;

use Lovata\Buddies\Models\User;
use Carbon\Carbon;
use Model;

/**
 * Model
 */
class Product extends Model
{
	use \October\Rain\Database\Traits\Validation;
	use \October\Rain\Database\Traits\Sortable;
	use \October\Rain\Database\Traits\Sluggable;

	protected $slugs = ['slug' => 'title'];
	/**
	 * @var string The database table used by the model.
	 */
	public $table = 'chronos_app_products';

	/**
	 * @var array Validation rules
	 */
	public $rules = [
	];

	public $belongsToMany = [
		'users' => [
			'Lovata\Buddies\Models\User',
			'table' => 'chronos_app_products_users',
			'key' => 'product_id',
			'otherKey' => 'user_id'
		]
	];

	public $hasMany = [
		'transactions' => 'Chronos\App\Models\Transaction'
	];

	// Проверяем, купил ли пользователь с id = iUserId данный продукт
	// public function purchasedByUser($iUserId) {
	// 	User::find($iUserId)
	// 	return 'lolo';
	// }

	public function scopePaid($obQuery) {
		return $obQuery->where('status', 'PAID');
	}

	public function getCreatedAtAttribute($date)
	{
		return Carbon::createFromFormat('Y-m-d H:i:s', $date)->format('d-m-Y H:i');
	}

	public function scopeIsWebinar($query, $webinar = true) {
			return $query->where('is_webinar', $webinar);
	}
}

<?php namespace Chronos\App\Models;

use Carbon\Carbon;
use Model;

/**
 * Model
 */
class Transaction extends Model
{
	use \October\Rain\Database\Traits\Validation;


	/**
	 * @var string The database table used by the model.
	 */
	public $table = 'chronos_app_transactions';

	/**
	 * @var array Validation rules
	 */
	public $rules = [
	];

	public $belongsTo = [
		'user' => 'Lovata\Buddies\Models\User',
		'product' => 'Chronos\App\Models\Product',
		'buyer' => [
			'Lovata\Buddies\Models\User', 'key' => 'buyer_id', 'otherKey' => 'id'
		],
		'fromuser' => [
			'Lovata\Buddies\Models\User', 'key' => 'from_user', 'otherKey' => 'id'
		]
	];


	public function scopeFromUser($query)
	{
		//dump($query);
		return $query
			->join('chronos_app_products as p', 'chronos_app_transactions.product_id', '=', 'p.id')
			->join('lovata_buddies_users as u', 'chronos_app_transactions.from_user', '=', 'u.id')
			->select('chronos_app_transactions.*', 'p.title as product_title', 'u.name as user_name');
		//return $query->join('chronos_commerce_series as s', 'chronos_commerce_opravas.series_id', '=', 's.id')->join('chronos_commerce_brands as b', 's.brand_id', '=', 'b.id')->select('chronos_commerce_opravas.*', 's.price as price', 's.discount', 's.isnew', 'b.title as brand_title')->orderBy($val, $dir)->byNew($query, $val, $dir)->orderBy('b.price', 'desc')->orderBy('s.title', 'asc')->orderBy('title', 'asc');
	}

	public function getCreatedAtAttribute($date)
	{
		return Carbon::createFromFormat('Y-m-d H:i:s', $date)->format('d-m-Y H:i');
	}
}

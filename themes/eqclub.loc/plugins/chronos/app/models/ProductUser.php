<?php namespace Chronos\App\Models;

use Model;

/**
 * Model
 */
class ProductUser extends Model
{
	use \October\Rain\Database\Traits\Validation;


	/**
	 * @var string The database table used by the model.
	 */
	public $table = 'chronos_app_products_users';

	/**
	 * @var array Validation rules
	 */
	public $rules = [
	];

	public $belongsTo = [
		'user' => 'Lovata\Buddies\Models\User'
	];

}

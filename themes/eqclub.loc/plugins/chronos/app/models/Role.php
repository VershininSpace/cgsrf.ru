<?php namespace Chronos\App\Models;

use Model;

/**
 * Model
 */
class Role extends Model
{
	use \October\Rain\Database\Traits\Validation;
	use \October\Rain\Database\Traits\Sortable;

	/**
	 * @var string The database table used by the model.
	 */
	public $table = 'chronos_app_roles';

	/**
	 * @var array Validation rules
	 */
	public $rules = [
	];

	public $belongsTo = [
		'role' => 'Vdomah\Roles\Models\Role'
	];

	public $belongsToMany = [
		'users' => [
			'Lovata\Buddies\Models\User',
			'table'    => 'chronos_app_roles_users',
			'key'      => 'role_id',
			'otherKey' => 'user_id'
		]
	];
}

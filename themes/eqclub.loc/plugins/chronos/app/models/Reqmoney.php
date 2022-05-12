<?php namespace Chronos\App\Models;

use Chronos\App\Components\AppComponent;
use Lovata\Buddies\Models\User;
use Model;

/**
 * Model
 */
class Reqmoney extends Model
{
	use \October\Rain\Database\Traits\Validation;


	/**
	 * @var string The database table used by the model.
	 */
	public $table = 'chronos_app_reqmoneys';

	/**
	 * @var array Validation rules
	 */
	public $rules = [
	];

	public $belongsTo = [
		'user' => 'Lovata\Buddies\Models\User'
	];

	public function beforeUpdate()
	{
		// Проверить - если исполнено, то списать деньги со счета пользователя и создать транзакцию
		if ($this->is_closed) {
			// Закрыто, ничего не делаем
		} else if (!$this->is_closed && ($this->is_accepted or $this->is_partial)) {
			$user = User::find($this->user_id);
			$user->balance = $user->balance - $this->total;
			$user->save();
			// Создаем транзакцию с минусом
			$this->is_closed = true;
			$app = new AppComponent();
			$app->moneyReqTransaction($this->total, $this->user_id);
		}
	}
}

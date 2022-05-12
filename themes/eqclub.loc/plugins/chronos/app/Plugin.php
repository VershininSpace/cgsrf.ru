<?php namespace Chronos\App;

use Chronos\App\Components\AppComponent;
use Illuminate\Support\Facades\Mail;
use System\Classes\PluginBase;
use Lovata\Buddies\Models\User;
//use Illuminate\Support\Facades\Input;
use Lovata\Buddies\Classes\Item\UserItem;

use Chronos\App\Classes\GetCourseExchange\GetCourseUser; 

use Vdomah\Roles\Models\Role;
use Input;

use Config;

class Plugin extends PluginBase
{
	public function registerComponents()
	{
		return [
			'Chronos\App\Components\AppComponent' => 'AppComponent',
			'Chronos\App\Components\CommerceComponent' => 'CommerceComponent',
			//'Chronos\Commerce\Components\ContactComponent' => 'ContactComponent',
			//'Chronos\Commerce\Components\CartComponent' => 'CartComponent',
			//'Chronos\Commerce\Components\OrdersComponent' => 'OrdersComponent'
		];
	}

	public function registerSettings()
	{
	}

	public function boot()
	{	
		
		//dd(GetCourseUser::all());

		
		UserItem::extend(function($obUserItem) {
					
			// Метод для проверки у Пользователя наличия продукта по его ID
			$obUserItem->addDynamicMethod('hasProduct', function($iProductId) use ($obUserItem) {

				$obModel = $obUserItem->getObject();
				$obProduct = $obModel->products()->Paid()->where('product_id', $iProductId)->first();

				if ($obProduct)
				 	return true;

				return false;
			});
	   });

		

		User::extend(function ($user) {
			$user->addCachedField(['childrens', 'balance', 'role', 'roles', 'products', 'children_count', 'reqmoneys']);

			$user->belongsToMany['products'] = [
				\Chronos\App\Models\Product::class,				
				'table' => 'chronos_app_products_users',
				// 'key' => 'product_id',
				// 'otherKey' => 'user_id',
				// 'pivot' => ['status', 'payment_id', 'payment_at', 'paid', 'product_id', 'user_id', 'pay_url', 'price', 'title'],
			];

			$user->addDynamicMethod('getReqmoneysAttribute', function () use ($user) {
				$ch = $user->reqmoneys()->get();
				if($ch){
					return $ch->toArray();
				}
				return [];
			});

			$user->addDynamicMethod('getChildrensAttribute', function () use ($user) {
				$ch = $user->childrens()->get();
				if ($ch) {
					return $ch->toArray();
				}
				return [];
			});

			$user->addDynamicMethod('getRoleAttribute', function () use ($user) {
				$role = $user->role()->with('role')->first();
				if ($role) {
					return $role->toArray();
				}
				return [];
			});

			$user->addDynamicMethod('getRolesAttribute', function () use ($user) {
				$roles = $user->roles()->get();
				if ($roles) {
					return $roles->toArray();
				}
				return [];
			});

			$user->addDynamicMethod('getProductsAttribute', function () use ($user) {
				$ch = $user->products()->get();
				if ($ch) {
					return $ch->toArray();
				}
				return [];
			});

			$user->addDynamicMethod('getPurchasesAttribute', function () use ($user) {
				$purchases = $user->products()->get();
				//dump($purchases);
				//return;
				if (empty($purchases)) {
					return 'Нет';
				}
				// return array_reduce($purchases, function ($sum, $e){
				// 	if($e['pivot']['price'] and $e['pivot']['paid']){
				// 		return $sum + $e['pivot']['price'];
				// 	} else {
				// 		return $sum + 0;
				// 	}
				// }, 1);
				//return number_format($purchases->sum('price'), 0, '', ' ');
			});

			$user->bindEvent('model.beforeCreate', function () use ($user) {
				$ref = (int) filter_var(Input::get('ref'), FILTER_SANITIZE_NUMBER_INT);

				$user->parent = $ref;
				$mk_date = Input::get('current_date');
				if($mk_date){
					$prop = $user->property;
					$prop['mk_date'] = $mk_date;
					$user->property = $prop;
				}

				$role = Role::where('code', 'user')->first()->id;
				$user->role_id = $role;
				/*
				if (Input::get('User')['password']) {
					$data = ['name' => Input::get('User')['name'], 'email' => Input::get('User')['email'], 'password' => Input::get('User')['password']];
					$app = new AppComponent();
					$app->sendRegEmail($data);
				}*/
			});

		});
	}
}

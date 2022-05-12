<?php

namespace Chronos\App\Components;

use Carbon\Carbon;
use Chronos\App\Models\Product;
use Chronos\App\Models\ProductUser;
use Chronos\App\Models\Role;
use Chronos\App\Models\RoleUser;
use Chronos\App\Models\Transaction;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Lovata\Buddies\Components\Registration;
use Lovata\Buddies\Models\User;
use SimpleXMLElement;

class CommerceComponent extends \Cms\Classes\ComponentBase
{
	public function componentDetails()
	{
		return [
			'name' => 'CommerceComponent',
			'description' => 'Функции '
		];
	}

	public function getUserId()
	{
		return Session::get('buddies_user_auth')[0];
	}


	public function test()
	{
		$arr = [
			"buyer" => 304,
			"product_id" => 8,
			"user_id" => 303,
			"from_user" => 304,
			"level" => 1,
			"enroll" => 1,
			"product_type" => "product",
			"total" => 0.4
		];

		$transaсtion = new Transaction();
		$transaсtion->user_id = $arr['user_id'];
		$transaсtion->product_id = $arr['product_id'];
		$transaсtion->total = $arr['total'];
		$transaсtion->from_user = $arr['from_user'];
		$transaсtion->level = $arr['level'];
		$transaсtion->enroll = $arr['enroll'];
		$transaсtion->buyer_id = $arr['buyer'];
		$transaсtion->product_type = $arr['product_type'];
		$transaсtion->save();
		dump($transaсtion);
	}

	/*** ДЕЙСТВИЯ ПРИ ПОКУПКЕ СТАТУСА */
	public function onBuyStatus()
	{
		$user_id = $this->getUserId();
		$role_id = Input::get('id');
		$backurl = Input::get('backurl');
		$role = Role::with('role')->find($role_id);
		$user = User::with('parent', 'role', 'roles')->find($user_id);
		$role_user = RoleUser::where('user_id', $user_id)->where('role_id', $role_id)->first();

		if ($role_user) {

			return [
				'status' => 'error',
				'message' => 'Вы уже купили этот статус'
			];

		} else {
			if ($role->role->code == 'partner') {
				$user->roles()->attach($role_id);
				return $this->makeStatusPayment($role, $backurl, $user);
				//return 'Хочу партнером стать';
			} else if ($role->role->code == 'franch') {
				$email = Input::get('email');
				$check = $this->checkEmail();
				if ($check['status']) {
					$user->roles()->attach($role_id);
					return $this->makeStatusPayment($role, $backurl, $user, $email);
				} else {
					return [
						'status' => 'error',
						'message' => $check['message']
					];
				}
				//return 'Хочу стать франчайзи';
			}


			return [
				'status' => 'success',
				'message' => 'Статус куплен'
			];
		}

	}

	/** ТЕНЕВАЯ РЕГИСТРАЦИЯ */
	public function shadowRegister($data)
	{
		$app_comp = new AppComponent();

		$password = bin2hex(random_bytes(4));
		$rd = [
			'name' => $data['name'],
			'email' => $data['email'],
			'phone' => $data['phone'],
			'password' => $password,
			'password_confirmation' => $password
		];

		$reg = new Registration();
		$ret = $reg->onAjaxHidden($rd);
		if ($ret['status'] == true) {
			$app_comp->sendRegEmail($rd);
			$user_id = $ret['data'];
			$user = User::find($user_id);
			$user->is_activated = 1;
			$user->activated_at = Carbon::now();
			if ($data['parent']) $user->parent_id = $data['parent'];
			$user->role = $data['role'];
			$user->save();
		}

		return $ret;
	}

	/** ПРОВЕРЯЕМ ДОСТУПНОСТЬ ЕМЕЙЛА*/
	public function checkEmail()
	{
		$reg = new Registration();
		$ret = $reg->onCheckEmail();
		return $ret->original;
	}

	/** СОЗДАЕМ ЗАПИСЬ О СТАТУСЕ В БАЗЕ И ПЕРЕХОДИМ К ОПЛАТЕ*/
	public function makeStatusPayment($role, $backurl, $user, $email = null)
	{
		$app = new AppComponent();
		$payment = $app->makeQiwiPayment($user, '', $role->price);
		//return $payment;
		//$payment = $this->makePayment($role, $backurl);
		$payment_id = $payment['billId'];
		$user_role = RoleUser::where('user_id', $user->id)->where('role_id', $role->id)->first();
		if ($email) $user_role->franch_email = $email;
		$user_role->payment_id = $payment_id;
		$user_role->price = $role->price;
		$user_role->status = $payment['status']['value'];
		$user_role->pay_url = $payment['payUrl'];
		$user_role->save();

		return Redirect::to($payment['payUrl']);
	}

	/** ДАЁМ СТАТУС ПОЛЬЗОВАТЕЛЮ */
	public function setStatusToUser($role_id, $user_id)
	{
		$user = User::find($user_id);
		$role = Role::with('role')->where('id', $role_id)->first();
		if ($role->role->code == 'partner') {
			$role_id = $role->role->id;
			$user->role = $role_id;
			$user->save();
		} else {
			$this->createFranchStatus($role, $user);
		}
	}

	public function createFranchStatus($role, $user)
	{
		//$role = Role::with('role')->find(2);
		//$user = User::with('parent')->find(55);
		$role_id = $role->id;
		$user_id = $user->id;
		$role_user = RoleUser::where('user_id', $user_id)->where('role_id', $role_id)->first();
		$email = $role_user->franch_email;
		$parent_id = ($user->parent) ? $user->parent->id : '';
		$role_code = $role->role->id;
		$data = [
			'name' => $user->name,
			'email' => $email,
			'phone' => $user->phone,
			'parent' => $parent_id,
			'role' => $role_code
		];

		$this->shadowRegister($data);
	}

	/** Бабло пользователю */
	public function addBabloToUser($user_id, $sum)
	{
		$user = User::find($user_id);
		$balance = $user->balance;
		$total = $user->total;
		$user->balance = $balance + $sum;
		$user->total = $total + $sum;
		$user->save();
		return;
	}

	/** PYRAMID */
	public function babloToNetwork($user_id, $product_id, $product_type, $price = null)
	{
		$user = User::with('parent', 'role.role')->find($user_id);

		if ($product_type == 'product') {
			$product = Product::find($product_id);
			if (!$price) {
				$price = $product->price;
			}
			$sum = $this->percent($price, $product->percent);
		} else {
			$product = Role::find($product_id);
			$sum = $this->percent($product->price, $product->percent);
		}

		$n = 1;

		$network = [];

		if (isset($user->parent)) {
			$nu = $user;

			/** Рекурсия по родителям пользователя */
			while ($n <= 9) {
				if (isset($nu->parent)) {

					$role = $nu->parent->role['code'];

					$transaction = [
						'buyer' => $user_id,
						'product_id' => $product_id,
						'user_id' => $nu->parent->id,
						'from_user' => $nu->id,
						'level' => $n,
						'enroll' => 1,
						'product_type' => $product_type
					];

					$sum = $sum / 2;
					$transaction['total'] = $sum;

					//echo $nu->parent->name." == Пользователь получает - $sum рублей <br>";

					if($user_id && $nu->id && $n){
						$this->createTransaction($transaction);
						$this->addBabloToUser($nu->parent->id, $sum);
					}

					/*if ($role == 'user') {
						$sum = $sum / 2;
						$transaction['total'] = $sum;
						if ($n == 1) {
							$this->createTransaction($transaction);
							$this->addBabloToUser($nu->parent->id, $sum);
							/// echo "$u_name Хоть и пользователь он, но получит свой кусон - $sum рублей";
						} else {
							$transaction['enroll'] = 0;
							$this->createTransaction($transaction);
							/// echo "$u_name Это пользователь сраный. Не получит нихуя он. - а мог бы взять $sum рублей";
						}
					} else if ($role == 'partner') {
						$sum = $sum / 2;
						$transaction['total'] = $sum;
						$this->createTransaction($transaction);
						$this->addBabloToUser($nu->parent->id, $sum);
						/// echo "$u_name Тут партнер у нас могучий. Получает свою кучу. - $sum рублей";
					} else if ($role == 'franch') {
						$transaction['total'] = $sum;
						$this->createTransaction($transaction);
						$this->addBabloToUser($nu->parent->id, $sum);
						/// echo "$u_name Тут франчайзи, мать твою. Всех вертел он на хую - $sum рублей";
						break;
					}*/

					$n++;
					$nu = User::with('parent', 'role.role')->find($nu->parent->id);

				} else {
					break 1;
				}
			}
			return;
		}
	}

	/** Проверка всех платежей */
	public function checkApiPayments()
	{
		$this->apiPayments();
		$this->showPaids();
	}

	public function apiPayments()
	{
		//$user_id = $this->getUserId();
		$productPayments = ProductUser::whereNotNull('pay_url')->get();
		$roleUser = RoleUser::whereNotNull('pay_url')->get();
		//dd($productPayments);
		//$productPayments = ProductUser::where('status', 'WAITING')->get();
		//$roleUser = RoleUser::where('status', 'WAITING')->get();

		if (count($productPayments) > 0) {
			$products = $this->checkProductPayments($productPayments, 'product');
		} else {
			$products = [];
		}

		if (count($roleUser) > 0) {
			$roles = $this->checkProductPayments($roleUser, 'status');
		} else {
			$roles = [];
		}
	}

	public function showPaids()
	{
		$paids = ProductUser::where('status', 'PAID')->with('user.parent')->orderBy('payment_at', 'desc')->limit(500)->get();
		$n = 1;
		echo '<div style="padding:30px;">';
		foreach ($paids as $pay) {
			if ($pay->price) {
				echo $pay->id . '. ' . $pay->payment_at . "<br> Оплата " . $pay->price . " руб. от пользователя " . $pay->user->name . " (id: " . $pay->user->id . ', ' . $pay->user->email . ", " . $pay->user->phone . "). Пригласил: " . $pay->user->parent->id . ' - ' . $pay->user->parent->name . ' - ' . $pay->user->parent->email;
				echo '<br><br>';
				$n++;
			}
		}
		echo '</div>';
	}

	/** ПРОВЕРКА ПЛАТЕЖЕЙ ПОЛЬЗОВАТЕЛЯ ПРИ ПЕРЕХОДЕ С КАССЫ */
	public function checkPayments()
	{
		$user_id = $this->getUserId();
		$productPayments = ProductUser::where('user_id', $user_id)->whereNotNull('pay_url')->get();
		$roleUser = RoleUser::whereNotNull('pay_url')->where('user_id', $user_id)->get();
		//$productPayments = ProductUser::where('user_id', $user_id)->where('status', 'WAITING')->get();
		//$roleUser = RoleUser::where('status', 'WAITING')->where('user_id', $user_id)->get();

		if (count($productPayments) > 0) {
			$products = $this->checkProductPayments($productPayments, 'product', $user_id);
		} else {
			$products = [];
		}

		if (count($roleUser) > 0) {
			$roles = $this->checkProductPayments($roleUser, 'status', $user_id);
		} else {
			$roles = [];
		}

		$ret = [
			'products' => $products,
			'roles' => $roles
		];

		return $ret;
	}

	public function sendToGetCourse($data)
	{
		$accountName = 'vybor';
		$secretKey = 'gqvh1sMl85gkVpszW2B5O9a4Wq4yWoMKu1YRuYhvvUT1hMyliU5spypJj13ntPdCtfs81NgW8ofJ04CsVruSnqN5cZeiKFIfyleOUL3RchBhYnn6oOOtCMnEecWstkXj';
		
		$user_eq = User::find($data['user_id']);
		$product = Product::find($data['product_id']);

		$user = [];
		$user['user']['email'] = $user_eq->email;
		$user['user']['phone'] = $user_eq->phone;
		$user['user']['first_name'] = $user_eq->name;
		$user['user']['group_name'] = [$product->title];
		//$user['system']['refresh_if_exists'] = 1;
		
		$json = json_encode($user);
		$base64 = base64_encode($json);
		
		if( $curl = curl_init() ) {
			curl_setopt($curl, CURLOPT_URL, 'https://' . $accountName . '.getcourse.ru/pl/api/users');
			curl_setopt($curl, CURLOPT_RETURNTRANSFER,true);
			curl_setopt($curl, CURLOPT_POST, true);
			curl_setopt($curl, CURLOPT_POSTFIELDS, 'action=add&key=' . $secretKey . '&params=' . $base64);
			$out = curl_exec($curl);
			//echo $out;
			curl_close($curl);
		} else {
			//echo 'Failed initialization';
		}
	}

	public function checkProductPayments($payments, $type, $user_id = null)
	{
		$mrh_login = "eqclub.ru";
		$mrh_pass2 = "xwvIR2YAZew48gfQ72XE";
		$tinkoff_token = 'N2M1MmQxNzgtMjliNi00N2I0LWJiNDEtMzJhNjVhYmY5YTViOk9sZWdza29iZWx0c3luZmYyMjlhcGllcWNsdWI=';

		$ret = [];
		$app = new AppComponent();


		$payments->each(function ($item, $i) use (&$ret, $mrh_login, $mrh_pass2, $user_id, $type, $tinkoff_token) {

			$mes = [];
			$status = null;
			$payment_id = $item->payment_id;
			$signature = md5("$mrh_login:$payment_id:$mrh_pass2");

			if (!$user_id) {
				$user_id = $item->user_id;
			}

			if (strpos($item->pay_url, 'tinkoff')) {
				$curl = curl_init();
				curl_setopt_array($curl, array(
				  CURLOPT_URL => "https://forma.tinkoff.ru/api/partners/v2/orders/$payment_id/info",
				  CURLOPT_RETURNTRANSFER => true,
				  CURLOPT_ENCODING => '',
				  CURLOPT_MAXREDIRS => 10,
				  CURLOPT_TIMEOUT => 0,
				  CURLOPT_FOLLOWLOCATION => true,
				  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
				  CURLOPT_CUSTOMREQUEST => 'GET',
				  CURLOPT_HTTPHEADER => array(
					'Content-Type: application/json',
					"Authorization: Basic $tinkoff_token"
				  ),
				));
				$response = json_decode(curl_exec($curl));
				curl_close($curl);
				if (isset($response->status)) {
					switch ($response->status) {
						case 'canceled':
							$status = 'CNC';
							break;
						
						case 'rejected':
							$status = 'CNC';
							break;
	
						case 'issued':
							$status = 'PAID';
							break;
					}
				}
			};
			if (strpos($item->pay_url, 'robokassa')) {
				$curl = curl_init();
				curl_setopt_array($curl, array(
				CURLOPT_URL => 'https://auth.robokassa.ru/Merchant/WebService/Service.asmx/OpState',
				CURLOPT_RETURNTRANSFER => true,
				CURLOPT_ENCODING => '',
				CURLOPT_MAXREDIRS => 10,
				CURLOPT_TIMEOUT => 0,
				CURLOPT_FOLLOWLOCATION => true,
				CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
				CURLOPT_CUSTOMREQUEST => 'POST',
				CURLOPT_POSTFIELDS => array('MerchantLogin' => $mrh_login,'InvoiceID' => $payment_id,'Signature' => $signature),
				));
				$response = curl_exec($curl);
				curl_close($curl);
				if (strpos($response, '<State>') == false) {
					return;
				}
				$xml = new SimpleXMLElement($response);
				$code_status = (int)$xml->State->Code;

				switch ($code_status) {
					case 10:
						$status = 'CNC';
						break;
					
					case 80:
						$status = 'CNC';
						break;

					case 100:
						$status = 'PAID';
						break;
				}
			}

				if ($status == 'CNC') {
					$item->delete();
					$mes['type'] = 'error';
					$mes['message'] = 'Отмена оплаты.';

				} else if ($status == 'PAID') {
					$mes['type'] = 'success';
					$mes['message'] = 'Успешная оплата. Поздравляем с покупкой! Обновите страницу.';
					$item->status = 'PAID';
					$item->payment_at = Carbon::now();
					$item->accepted = 1;
					$item->paid = 1;
					$item->pay_url = NULL;
					$item->save();
					if ($type == 'status') $this->setStatusToUser($item->role_id, $user_id);
					$this->babloToNetwork($user_id, ($type == 'product' ? $item->product_id : $item->role_id), $type, $item->price);
					$this->sendToGetCourse(['user_id' => $user_id, 'product_id' => $item->product_id]);
				} else {
					$mes['type'] = 'warning';
					$mes['message'] = 'Оформление не закончено. Вы можете завершить оплату, пройдя по ссылке во вкладке "Мои покупки"';
				}
			
			$ret[] = $mes;
		});

		return $ret;

	}

	public function percent($sum, $perc)
	{
		return ($sum / 100) * $perc;
	}

	/** Создаем транзакцию */
	public function createTransaction($arr)
	{
		//dump($arr);
		extract($arr);
		$transaсtion = new Transaction();
		$transaсtion->user_id = $arr['user_id'];
		$transaсtion->product_id = $arr['product_id'];
		$transaсtion->total = $arr['total'];
		$transaсtion->from_user = $arr['from_user'];
		$transaсtion->level = $arr['level'];
		$transaсtion->enroll = $arr['enroll'];
		$transaсtion->buyer_id = $arr['buyer'];
		$transaсtion->product_type = $arr['product_type'];
		$transaсtion->save();
	}
	
}
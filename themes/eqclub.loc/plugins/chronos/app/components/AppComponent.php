<?php

namespace Chronos\App\Components;

use Carbon\Carbon;
use Chronos\App\Models\Article;
use Chronos\App\Models\Comment;
use Chronos\App\Models\Product;
use Chronos\App\Models\ProductUser;
use Chronos\App\Models\Promocode;
use Chronos\App\Models\Reqmoney;
use Chronos\App\Models\Role;
use Chronos\App\Models\Transaction;
use Input;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Lovata\Buddies\Classes\Item\UserItem;
use Lovata\Buddies\Components\Registration;
use Lovata\Buddies\Models\User;
use Cms\Classes\Theme;
use Illuminate\Http\Request;
use Qiwi\Api\BillPayments;
use Lovata\Buddies\Facades\AuthHelper;

use File;
use Yaml;


use Kharanenka\Helper\Result;
use Lang;

use Config;

define('STDIN',fopen("php://stdin","r"));

class AppComponent extends \Cms\Classes\ComponentBase
{
	public function componentDetails()
	{
		return [
			'name' => 'AppComponent',
			'description' => 'Функции почты и т.д.'
		];
	}

	/** Экспорт пользователей */
	public function expUsers()
	{
		$users = User::all();

		$prod = [];
		foreach ($users as $k => $u) {
			$prod[$k]['name'] = $u->name;
			$prod[$k]['email'] = $u->email;
			$prod[$k]['phone'] = $u->phone;
			$prod[$k]['id'] = $u->id;
			$prod[$k]['sdelka'] = $u->name . ' ID ' . $u->id;
		}

		//dd($prod);

		$output = fopen("php://output", 'w') or die("Can't open php://output");
		header("Content-Type:application/csv");
		header("Content-Disposition:attachment;filename=pressurecsv.csv");
		fputcsv($output, array('name', 'email', 'phone', 'id', 'sdelka'));
		foreach ($prod as $product) {
			fputcsv($output, $product);
		}
		fclose($output) or die("Can't close php://output");
	}

	public function getUserById($id)
	{
		return User::find($id);
	}

	public function onUserUpdate()
	{
		$user_id = $this->getUserId();
		$user = User::find($user_id);
		$user->name = Input::get('name');
		$user->email = Input::get('email');
		$user->phone = Input::get('phone');
		$user->property = Input::get('property');
		$user->save();
	}

	/** Экспорт пользователей в Google Sheets*/
	public function userToCsv($period)
	{
		$key = Input::get('key');
		$type = Input::get('type');

		if ($key != 'Ngm5iAv8k2wBBkekQFP12AKoWI2mM5Lq') return;

		$users = User::with('parent_user', 'products')->orderBy('id', 'desc');

		if ($period == 'today') $users = $users->whereDate('created_at', Carbon::today());

		$users = $users->get();

		$arr =[];
		foreach ($users as $user) {

			$parent = '';

			if ($user->parent)
				$parent = $user->parent->id . ' - ' . $user->parent->name . ' - ' . $user->parent->phone;	
			
			//$parent = $user->parent_id;

			if ($type == 'pays') {
				foreach ($user->products as $product) {
					if($product['pivot']['status'] == 'PAID') {
						$date_time = Carbon::parse($product['pivot']['payment_at'])->addHours(3);
						$arr[] = [
							'id' => $user->id,
							'name' => $user->name,
							'email' => $user->email,
							'phone' => $user->phone,
							'prod_title' => $product['title'],
							'prod_price' => $product['price'],
							'pay' => $product['pivot']['price'],
							'date' => $date_time->toDateString(),
							'time' => $date_time->toTimeString(),
							'payment_at' => $date_time->format('Y-m-d H:i:s')
						];
					}
				}

				$collection = collect($arr);
				$sorted = $collection->sortByDesc('payment_at');
				$arr = $sorted->values()->all();

			} else {
				$arr[] = [
					'id' => $user->id,
					'name' => $user->name,
					'email' => $user->email,
					'phone' => $user->phone,
					'refer' => $parent,
					'balance' => $user->balance,
					'total' => $user->total,
					'created_at' => $user->created_at,
				];
			}
		}

		
		$output = fopen("php://output", 'w') or die("Can't open php://output");
		
		if ($type == 'pays') {
			fputcsv($output, ['id', 'Имя', 'Email', 'Телефон', 'Название', 'Стоимость продукта', 'Оплата',  'Дата оплаты', 'Время оплаты', 'Дата Время']);
		} else {
			fputcsv($output, ['id', 'Имя', 'Email', 'Телефон', 'Refer', 'Баланс', 'Total', 'Создан']);
		}
		foreach ($arr as $item) {
			fputcsv($output, $item);
		}
		fclose($output) or die("Can't close php://output");
	}

	/** Делаем всех пользователями */
	public function makeUsers()
	{
		$users = User::get();
		$role = Role::where('code', 'user')->first()->id;
		foreach ($users as $user) {
			$user->role_id = $role;
			$user->save();
		}
		echo 'ok';
	}

	/** СОЗДАЁМ ЗАПРОС НА ВЫВОД ДЕНЕГ! **/
	public function onMakeMoneyreq()
	{
		$total = Input::get('total');
		$user_id = $this->getUserId();
		$mr = new Reqmoney();
		$mr->user_id = $user_id;
		$mr->total = $total;
		$mr->status = 'Новый';
		$mr->save();
		return $mr;

	}

	/** Транзакция на вывод денег */
	public function moneyReqTransaction($total, $user)
	{
		$sum = (int)('-' . abs($total));
		$transaction = new Transaction();
		$transaction->total = $sum;
		$transaction->user_id = $user;
		$transaction->save();
	}


	/** ПОЛУЧАЕМ ВСЯКОЕ */

	/** ПОЛУЧАЕМ ПРОДУКТЫ */
	public function getProducts($prods)
	{
		if (count($prods) > 0) {
			$ids = array_map(function ($n) {
				return $n['id'];
			}, $prods);
			return Product::whereNotIn('id', $ids)->get();
		} else {
			return Product::all();
		}
	}

	/** Получаем конкретный продукт */
	public function getProduct($product)
	{
		$product = Product::where('code', $product)->first();
		//dd($product);
		return $product;
	}

	public function getProductPage($slug)
	{
		$product = Product::where('slug', $slug)->first();
		//dd($product);
		return $product;
	}

	/** Получаем роли */
	public function getRoles()
	{
		$roles = \Chronos\App\Models\Role::with('role')->where('in_sale', 1)->get();
		return $roles;
	}

	/** Получаем тарифы (продукты для раздела Тарифы) */
	/* DEPRECATED */
	public function getTarifs()
	{
		$tarifs = Product::all();
		return $tarifs;
	}

	/** Получаем ID пользователя из сессии */
	public function getUserId()
	{
		return Session::get('buddies_user_auth')[0];
	}

	/** Получаем транзакции пользователя */
	public function getUserTransactions()
	{
		$uid = $this->getUserId();
		$transactions = Transaction::where('user_id', $uid)->whereNotNull('from_user')->FromUser()->orderBy('created_at', 'desc')->get();
		return $transactions;
	}

	/*** Get Children Payments */
	public function onGetChildrenPayments()
	{

		//return;
		$user_id = $this->getUserId();
		//$user_id = 193;
		//$user_id = 193;

		$transactions = Transaction::where('user_id', $user_id)->with('buyer.product_users', 'buyer.parent', 'fromuser', 'product')->orderBy('updated_at', 'desc')->get();
		//dd($transactions->toArray());
		return $transactions;
		//dd($transactions->toArray());


		$childrens = User::where('id', $user_id)->with('products')->with('childrens.transactions.product')->with(['childrens' => function ($q) {
			$q->whereHas('products', function ($query) {
				$query->where('status', 'PAID');
			});
		}])->first()->toArray();

		//dump($childrens);
		//return $childrens;

		if (count($childrens['childrens']) > 0) {
			//dd($childrens->toArray());

			function amount_array($arr, $lvl = 0)
			{
				static $users;
				$arr['amount'] = $lvl;
				if ($lvl !== 0) {
					$users[] = $arr;
				}
				$lvl++;

				foreach ($arr['childrens'] as $children) {
					if (is_array($children)) {
						amount_array($children, $lvl);
					}
				}
				return $users;
			}

			$users = amount_array($childrens);
			//dd($users);
			//dump($users);


			$transactions = [];
			foreach ($users as $user) {
				if (isset($user['transactions'])) {
					foreach ($user['transactions'] as $transaction) {
						$transaction['user'] = $user;
						$transactions[] = $transaction;
					}
				}
			}

			//dump($transactions);


			usort($transactions, function ($a, $b) {
				$date1 = Carbon::createFromTimeString($a['updated_at']);
				$date2 = Carbon::createFromTimeString($b['updated_at']);
				return $date1->gt($date2) ? -1 : 1;
			});

			//dump($transactions);
			return $transactions;
		} else {
			return [];
		}
	}

	/** ВСПОМОГАТЕЛЬНОЕ */

	public function addCashToParent($parent, $sum)
	{
		$parent = User::find($parent);
		if ($parent) {
			$parent_balance = $parent->balance;
			$parent_balance = $parent_balance + $sum;
			$parent->balance = $parent_balance;
			$parent->save();
		}
	}

	public function calculateSum($user, $price)
	{
		$children_count = $user->children_count;

		if ($children_count < 4) {
			$perc = $this->percent($price, 10);
		} else if ($children_count > 4 && $children_count < 11) {
			$perc = $this->percent($price, 15);
		} else if ($children_count > 11) {
			$perc = $this->percent($price, 20);
		}

		return $perc;
	}

	public function percent($sum, $perc)
	{
		return ($sum / 100) * $perc;
	}


	public function queueTest()
	{
		Queue::push('\Chronos\App\Classes\SendEmail@sendRegEmail', ['name' => '123', 'email'=>'sad@asd.com']);
		echo '123';
	}


	/** ОБРАБОТЧИКИ */
	public function onBuyProduct()
	{
		$product_id = Input::get('id');
		$promocode = Input::get('promocode');
		$promocode_value = Input::get('promocode_value');
		$tinkoff = Input::get('tinkoff');
		
		$url = Input::get('url');
		$uuid = Input::get('uuid');


		$product = Product::find($product_id);
		$user = User::find($this->getUserId());

		$price = $product->price - (($product->price / 100) * $promocode_value);
		$payment = [];

		if ($tinkoff) return ['price' => $price];

		if ($url) {
			$payment['payUrl'] = $url;
			$payment['billId'] = $uuid;
		} else {
			$payment = $this->makeRoboPayment($user, $product, $price);
		}

		$this->preorderProduct($user, $product, $payment, $price, $promocode . ' - ' . $promocode_value);

		/* Notify */
		$mail_data = [
			'name' => $user->name,
			'email' => $user->email,
			'current_date' => '-',
			'product' => $product->title,
			'promocode' => $promocode,
			'price' => $price
		];
		$this->sendOnBuyEmail($mail_data);

		UserItem::clearCache($this->getUserId());

		if (!$url) return Redirect::to($payment['payUrl']);
	}

	public function onClearUserCache()
	{
		$user_id = Session::get('buddies_user_auth')[0];
		UserItem::clearCache($user_id);
	}

	public function onDeleteProduct()
	{
		$product_id = Input::get('product_id');
		$user_id = Session::get('buddies_user_auth')[0];
		User::find($user_id)->products()->detach($product_id);
		UserItem::clearCache($user_id);
	}

	/** ПОКУПКА ПРОДУКТА ЧЕРЕЗ МОДАЛКУ С РЕГИСТРАЦИЕЙ */

	public function onModalBuyProduct()
	{
		/* Объявляем переменные */
		$email = Input::get('email');
		$name = Input::get('name');
		$phone = Input::get('phone');
		$promocode = Input::get('promocode') ? Input::get('promocode') : '';
		$current_date = Input::get('current_date') ? Input::get('current_date') : '-';
		$product_code = Input::get('product');
		$pay = Input::get('pay');

		$url = Input::get('url');
		$uuid = Input::get('uuid');

		/* Получаем или регистрируем пользователя */
		$user = $this->checkUserAndRegister($name, $email, $phone);

		AuthHelper::login($user);

		/* Если пользователь есть, покупаем продукт */
		if ($user) {
			if ($pay == 1) {

				/* Получаем продукт и промокод */
				$product = Product::where('code', $product_code)->first();
				$promo = $this->findPromocode($promocode);

				/* Если промокод есть, применяем его */
				if ($promo) {
					$price = $this->applyPromocode($promo, $product->price);
				} else {
					$price = $product->price;
				}

				/* Отправляем почту о покупке */
				$mail_data = [
					'name' => $name,
					'email' => $email,
					'phone' => $phone,
					'current_date' => $current_date,
					'product' => $product->title,
					'price' => $price,
					'promocode' => $promocode
				];
				$this->sendNotifyEmail($mail_data);

				/** Если есть промокод и он в обход оплаты, то сохраняем продукт и отправляем в сеть */
				if ($promo && $promo->is_secret === 1) {
					$user->products()->attach($product->id,
						[
							'status' => 'PAID',
							'accepted' => 1,
							'paid' => 1,
							'payment_at' => Carbon::now(),
							'payment_id' => 'nal',
							'pay_url' => NULL,
							'price' => $price
						]);
					$cc = new CommerceComponent();
					$cc->babloToNetwork($user->id, $product->id, 'product', $price);
					$payment = null;

				} else {

					if ($url) {
						$payment = [];
						$payment['payUrl'] = $url;
						$payment['billId'] = $uuid;
					} else {
						$payment = $this->makeRoboPayment($user, $product, $price);
					}

					$title_promo = null;
					if ($promo) $title_promo = $promo->code .' - ' . $promo->amount;
					if($payment) $this->preorderProduct($user, $product, $payment, $price, $title_promo);
				}

				return [
					'product' => $product,
					'user' => $user,
					'payment' => $payment ? $payment : false
				];

			} else {
				return [
					"user" => $user,
					'status' => false,
					'message' => 'Непредвиденная ошибка. Попробуйте еще раз'
				];
			}
		} else {
			return [
				'status' => false,
				'message' => 'Произошла ошибка. Попробуйте обновить страницу и сделать покупку еще раз'
			];
		}

	}

	/** Проверяем есть ли пользователь и в случае чего, регистрируем */

	/* В процессе DEPRECATED */
	public function checkUserAndRegister($name, $email, $phone)
	{
		$registration = new Registration();
		$check = $registration->onCheckEmailData($email);

		if ($check && $check->original['status'] === false) {
			$user = User::where('email', $email)->first();

			$password = bin2hex(random_bytes(4));
			$rd = [
				'name' => $user->name,
				'email' => $user->email,
				'phone' => $user->phone,
				'password' => $password,
			];

			$user->password = $password;
			$user->password_confirmation = $password;
			$user->save();
	
			$this->sendRegEmail($rd);

			return $user;
		} else {
			$reg = $this->shadowRegister(['name' => $name, 'email' => $email, 'phone' => $phone]);
			if (isset($reg) && $reg['status'] == true) {
				$user = User::where('email', $email)->first();
				return $user;
			} else {
				return false;
			}
		}
	}

	public function onModalBuyProduct2()
	{
		/** Переменные */
		$email = Input::get('email');
		$name = Input::get('name');
		$phone = Input::get('phone');
		$promocode = Input::get('promocode') ? Input::get('promocode') : '';
		$current_date = Input::get('current_date') ? Input::get('current_date') : '-';
		$product_code = Input::get('product');
		$pay = Input::get('pay');
		//return Input::get();

		$reg = new Registration();
		$check = $reg->onCheckEmailData($email);

		if ($check->original['status'] == false) {
			$user = User::where('email', $email)->first();
			if ($user) $usr = true;
		} else {
			$reg = $this->shadowRegister(['name' => $name, 'email' => $email, 'phone' => $phone]);
			if ($reg['status'] == true) {
				$user = User::where('email', $email)->first();
				//return $user;
			}
		}
		/** Получаем продукт и пользователя */

		if ($user && $pay == 1) {
			$product = Product::where('code', $product_code)->first();
			//$user_id = $user->id;
			//$user = User::find($user_id);

			$promo = $this->findPromocode($promocode);
			if ($promo) {
				$price = $this->applyPromocode($promo, $product->price);
			} else {
				$price = $product->price;
			}

			$mail_data = [
				'name' => $name,
				'email' => $email,
				'phone' => $phone,
				'current_date' => $current_date,
				'product' => $product->title,
				'price' => $price,
				'promocode' => $promocode
			];
			$this->sendNotifyEmail($mail_data);

			/** Если есть промокод и он в обход оплаты, то сохраняем продукт и отправляем в сеть */
			if ($promo && $promo->is_secret === 1) {
				$user->products()->attach($product->id,
					[
						'status' => 'PAID',
						'accepted' => 1,
						'paid' => 1,
						'payment_at' => Carbon::now(),
						'payment_id' => 'nal',
						'pay_url' => NULL,
						'price' => $price
					]);
				$cc = new CommerceComponent();
				$cc->babloToNetwork($user->id, $product->id, 'product', $price);
				$payment = null;

			} else {
				$payment = $this->makeRoboPayment($user, $product, $price);

				$title_promo = null;
				if ($promo) $title_promo = $promo->code . ' - ' . $promo->amount;
				$this->preorderProduct($user, $product, $payment, $price, $title_promo);
			}

			return [
				'product' => $product,
				'user' => $user,
				'payment' => $payment
			];
		} else {
			return $user;
		}

	}

	/* @Titamik Регистрация пользователя через модальное окно */

	public function onModalRegister() {
		$sEmail = post('email');
		$sName = post('name');
		$sPhone = post('phone');		
		
		// Get user by email
		$obUser = User::getByEmail($sEmail)->first();

		if (!empty($obUser)) {			
			return [
				'status' => false,
				'message' => 'Пользователь с таким Email уже существует'
			];			
		} else {
			$oStatus =$this->shadowRegister(['name' => $sName, 'email' => $sEmail, 'phone' => $sPhone]);
			return [
				'status' => true,
				'message' => 'Вы успешно зарегестрировались'
			];	
		}
		
	}

	public function shadowRegister($data)
	{
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
			//$this->sendRegEmail($rd);
			$user_id = $ret['data'];
			$user = User::find($user_id);
			$user->is_activated = 1;
			$user->activated_at = Carbon::now();
			$user->save();
		}

		return $ret;
	}

	public function sendNotifyEmail($data)
	{
		Mail::send('new_register_notify', $data, function ($message) {
			$email = 'milo@bk.ru';
			$message->to($email);
			//$message->cc('potapov@sojsintez.com');
			//$message->cc('21031213.266517@parser.amocrm.ru');
		});
	}

	public function sendOnBuyEmail($data)
	{
		Mail::send('new_buy_notify', $data, function ($message) {
			$email = 'milo@bk.ru';
			//$message->cc('potapov@sojsintez.com');
			$message->to($email);
		});
	}

	public static function sendRegEmail($data)
	{
		Mail::send('user.register', $data, function ($message) use ($data) {
			$name = $data['name'];
			$email = $data['email'];
			$message->to($email, $name);
		});
	}

	public function preorderProduct($user, $product, $transaction, $price = null, $title_promo = null)
	{
		$payment_id = $transaction['billId'];
		$payurl = $transaction['payUrl'];
		$user->products()->attach($product->id, ['payment_id' => $payment_id, 'pay_url' => $payurl, 'price' => $price, 'title' => $title_promo]);
	}

	/** Robokassa */

	public function makeRoboPayment($user, $product, $price) {
		$mrh_login = "eqclub.ru";
		$mrh_pass1 = "tXR16zWuTGm6gVz68nUC";

		// order properties //2147483647
		$inv_id = (int)($user->id . $product->id . rand(100, 999));

		$inv_desc = "Оплата на сайте Eqclub.ru"; // invoice desc
		$out_summ = $price; // invoice summ

		// build CRC value
		$crc  = md5("$mrh_login:$price:$inv_id:$mrh_pass1");

		// build URL
		$url = "https://auth.robokassa.ru/Merchant/Index.aspx?MerchantLogin=$mrh_login&OutSum=$out_summ&InvId=$inv_id&Description=$inv_desc&SignatureValue=$crc";
		return [
			'billId' => $inv_id,
			'payUrl' => $url,
		];
	}

	/** QIWI */
	public function qiwiConnect()
	{
		$theme = Theme::getActiveTheme()->getCustomData();
		$qiwi_private = $theme->qiwi_private;
		$qiwi_public = $theme->qiwi_public;
		return $billPayments = new BillPayments($qiwi_private);
	}

	public function makeQiwiPayment($user, $product, $price)
	{
		$payment_id = bin2hex(random_bytes(4)) . '-' . bin2hex(random_bytes(4));
		$billPayments = $this->qiwiConnect();

		$fields = [
			'amount' => $price,
			'currency' => 'RUB',
			'comment' => 'Оплата на сайте Eqclub.ru',
			'expirationDateTime' => Carbon::now()->addDay()->format('Y-m-d\TH:i:s.uP'),
			'email' => $user['email'],
			'account' => $user['email']
		];

		/** @var \Qiwi\Api\BillPayments $billPayments */
		$response = $billPayments->createBill($payment_id, $fields);
		return $response;
	}

	public function onCheckQiwiPaymentStatus()
	{
	}

	public function clearUser()
	{
		UserItem::clearCache($this->getUserId());
	}

	public function returnPaymentStatus()
	{
		$cc = new CommerceComponent();
		$cc->apiPayments();

	}


	public function onRequestMoney()
	{
		$id = $this->getUserId();
		$user = User::find($id);

		//$themes = Theme::getActiveTheme()->getCustomData();
		//$to_mail = $themes->email;
		$to_mail = 'milo@bk.ru';

		$ret = [
			'name' => $user->name,
			'email' => $user->email,
			'franch_email' => $to_mail,
			'sum' => Input::get('summa'),
			'qiwi' => Input::get('qiwi'),
			'user_balance' => $user->balance,
			'message' => 'Запрос успешно отправлен'
		];

		Mail::send('requestpayment', $ret, function ($message) use ($ret) {
			$email = $ret['franch_email'];
			$message->to($email);
		});

		return $ret;
	}


	/** PROMOCODES */
	public function findPromocode($code)
	{
		if($code){
			return Promocode::where('code', $code)->first();
		}
		return false;
	}

	public function onCheckPromocode()
	{
		$code = Input::get('promocode');
		$promocode = $this->findPromocode($code);
		return $promocode;
	}

	public function applyPromocode($code, $price)
	{
		if ($code) {
			return $price - (($price / 100) * $code->amount);
		} else {
			return $price;
		}
	}


	/** BLOG **/
	public function getArticles()
	{
		$articles = Article::paginate(50);
		return $articles;
	}

	public function getLastComments()
	{
		$lc = Comment::with('article')->orderBy('created_at', 'desc')->take(3)->get();
		return $lc;
	}

	public function getArticle($slug)
	{
		//$article = Article::where('slug', $slug)->with('comments')->first();
		$article = Article::where('slug', $slug)->first();
		return $article;
	}

	public function getArticleById($id)
	{
		$article = Article::where('id', $id)->with('comments')->first();
		return $article;
	}

	public function getUriProduct($id)
	{
		return Product::findOrFail($id)->slug;
	}
}
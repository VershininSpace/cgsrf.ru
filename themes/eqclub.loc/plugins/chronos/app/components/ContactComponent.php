<?php

namespace Chronos\App\Components;

use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Mail;
use Lovata\Buddies\Models\User;

class ContactComponent extends \Cms\Classes\ComponentBase
{
	public function componentDetails()
	{
		return [
			'name' => 'ContactComponent',
			'description' => 'Функции почты и т.д.'
		];
	}

	// Прослушка событий. При определенных событиях отправлять почту

	public function testSend()
	{
		echo 'Test';
		$vars = ['name' => 'Joe', 'user' => 'Mary'];

		Mail::send('neworder', $vars, function ($message) {

			$message->to('admin@oprava.store', 'Администратор');
			//$message->subject('This is a reminder');

		});
	}


	public static function sendOrderMail($data)
	{
		Mail::send('neworder', $data, function ($message) use ($data) {

			//$message->to($address, $name = null);
			$message->to('admin@oprava.store', 'Администратор');

			if ($data['curator']['email']) {
				$message->to($data['curator']['email'], $data['curator']['name']);
			}
			//$message->subject('This is a reminder');

		});
	}

	public static function sendRegEmail($data)
	{
		Mail::send('mail.newuser', $data, function ($message) use ($data) {

			$name = $data['name'];
			$email = $data['email'];
			$message->to($email, $name);
			//$message->subject('This is a reminder');
		});
	}

	public static function sendCuratorNewUser($data)
	{
		Mail::send('mail.curator.newuser', $data, function ($message) use ($data) {

			$curator = User::where('id', $data['curator_id'])->select('name', 'email')->first();
			$сname = $curator->name;
			$сemail = $curator->email;
			$message->to($сemail, $сname);
			//$message->subject('This is a reminder');
		});
	}

	public function onSendInviteMail()
	{
		$data = Input::get();
		Mail::send('mail.invite', $data, function ($message) use ($data) {
			$name = $data['name'];
			$email = $data['email'];
			$message->to($email, $name);
		});
	}
}
<?php namespace Chronos\App\Classes;


class SendEmail
{
	public function fire($job, $data)
	{
		//
	}

	public function sendRegEmail($job, $data)
	{
		$app = new \Chronos\App\Components\AppComponent();
		$app->sendRegEmail($data);
		$job->delete();
	}
}
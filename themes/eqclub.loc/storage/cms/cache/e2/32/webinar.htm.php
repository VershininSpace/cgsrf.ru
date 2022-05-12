<?php 
use Chronos\App\Models\Product;class Cms626aff3905627706650877_9acf33e96cdada8da585cde0cca4d1b9Class extends Cms\Classes\LayoutCode
{

public function onStart(){
	$user = $this->UserData->get();
	$this['obUser'] = $user;
	$this['products'] = Product::isWebinar()->get();
}
}

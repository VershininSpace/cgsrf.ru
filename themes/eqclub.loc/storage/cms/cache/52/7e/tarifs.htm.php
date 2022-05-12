<?php 
use Chronos\App\Models\Product;class Cms626b087690dee805510519_060c1cfeeddede6f1fce9f37b1661ab0Class extends Cms\Classes\LayoutCode
{

public function onStart(){
	$user = $this->UserData->get();
	$this['obUser'] = $user;
	$this['products'] = Product::isWebinar(false)->get();
}
}

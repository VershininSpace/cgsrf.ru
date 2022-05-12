<?php 
class Cms626af4edaf253370185854_b6d0e3814b459b8b6a5fb6b8792f534eClass extends Cms\Classes\LayoutCode
{
public function onStart(){
$user = $this->UserData->get();
$this['obUser'] = $user;
$this['hdr'] = isset($_GET['hdr']) ? $_GET['hdr'] : '';
//$this->AppComponent->test();
$prod = $this->page->apiBag['staticPage']['settings']['components']['viewBag']['kurs'];
$this['product'] = $this->AppComponent->getProduct($prod);
}
}

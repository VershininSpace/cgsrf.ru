<?php 
class Cms626b08160a87b173999739_bd8f6099aa7ec14497a25f0eff420774Class extends Cms\Classes\LayoutCode
{
public function onStart(){
	$slug = $this->param('slug');

$product = $this->AppComponent->getProductPage($slug);
$this['item'] = $product;
$this->title = $this['item']['title'];

$user = $this->UserData->get();
$this['obUser'] = $user;

}
}

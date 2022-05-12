<?php 
class Cms626b0aefcadf5914351445_4a03f44ad9fc8958432a7e14dc0fada3Class extends Cms\Classes\LayoutCode
{
public function onStart(){
$this['url'] = $this->page->id;
$user = $this->UserData->get();
//dd($user);
//dd($this->UserData->get());
$this['obUser'] = $user;
if($user){
$this['payments'] = $this->CommerceComponent->checkPayments();
//$this->AppComponent->clearUser();
$user = $this->UserData->get();
$this['obUser'] = $user;
$this['products'] = $this->AppComponent->getProducts($user->products);
$this['transactions'] = $this->AppComponent->getUserTransactions();
$this['roles'] =  $this->AppComponent->getRoles();
$this['parent_user'] = $this->AppComponent->getUserById($user->parent_id);

//dd($this['payments']);
//dd($this['roles']);
}
//dd($this['products']);
//dd(json_encode($user->arModelData()['childrens']));
}
}

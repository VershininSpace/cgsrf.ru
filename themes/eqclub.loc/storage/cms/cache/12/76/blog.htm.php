<?php 
class Cms626b0306114ba754331901_aa64cc692dbc9115f83574704316a95aClass extends Cms\Classes\LayoutCode
{
public function onStart(){
$this->page->meta_title = $this->page->title;
$this['items'] = $this->AppComponent->getArticles();
$user = $this->UserData->get();
$this['obUser'] = $user;
//dump($this['items']);
//echo 'asd';
//$this->title = $this['cat']['title'];
//$this['slug'] = $slug;
}
}

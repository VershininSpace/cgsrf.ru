<?php 
class Cms626af5028fe9e298903031_b80e21cf9f329ae668a5256e3160a533Class extends Cms\Classes\LayoutCode
{
public function onStart(){
$this['obUser'] = $this->UserData->get();
$this['url'] = $this->page->id;
//dd($this['obUser']);
//dump($this['obUser']);
}
}

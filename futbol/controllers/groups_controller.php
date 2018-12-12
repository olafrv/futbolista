<?php

class GroupsController extends AppController{
  //var $scaffold;
	var $layout = "cake";
  function listAll(){ //Users
    if (!empty($this->params['requested'])){
      return $this->Group->find('list');
    }
  }
}


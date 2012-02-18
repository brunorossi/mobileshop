<?php

class IndexController extends Zend_Controller_Action
{

    public function init()
    {
    }

    public function indexAction()
    {
    	$em = $this->getInvokeArg('bootstrap')->entityManagers['shop'];
    	$shop = new App_Doctrine_Entity_Shop();
    	$shop->setAddress('Address');
    	$shop->setName('Name');
    	$shop->setZipCode('12345');
    	$em->persist($shop);
		$em->flush();    	
    }

}


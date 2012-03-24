<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{

   protected function _initRestRoute()
    {
        $this->bootstrap('frontController');
        $frontController = Zend_Controller_Front::getInstance();
        $restRoute = new Zend_Rest_Route($frontController);
        
        
		$route = new Zend_Controller_Router_Route(
		    ':module/:controller/:action/jsonp/*',
		    array(
		    	'module'     => 'shop',
		    	'controller' => 'index',
		        'action'     => 'post',
		    )
		);     
               
        $frontController->getRouter()->addRoute('rest', $restRoute);
        $frontController->getRouter()->addRoute('shop', $route);         

    }
	
	
}


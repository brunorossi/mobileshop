<?php

class Shop_IndexController extends Zend_Rest_Controller
{
	
	protected $_shopPersistanceService;
	
    public function init()
    {
    	$config = array();
    	
    	$config['shopEntityManager'] = $this->getInvokeArg('bootstrap')
    	                                    ->entityManagers['shop'];
    	                                    
		$config['tagPersistanceService'] = new Tag_Service_Persistance($config);
    	
		$config['shopForm'] = new Shop_Form_Shop($config);
    	
    	$this->_shopPersistanceService = new Shop_Service_Persistance($config);
    }
    
    /**
     * 
     * Retrieves a collection of entities from persistance layer
     */
    public function indexAction()
    {        	
    	$result = $this->_shopPersistanceService->fetch($this->getRequest()->getQuery());

    	$json = $this->_helper
    	             ->doctrine2Json
    	             ->encode($result, $this->_shopPersistanceService->getShopEntityManager());
    	
        $this->getResponse()->setHttpResponseCode(400)->appendBody($json);          	
    }

    /**
     * 
     * Retrieves an entity from the persistance layer
     */
    public function getAction()
    {
		$result = $this->_shopPersistanceService->fetchById($this->getRequest()->getQuery());
    	
		$json = $this->_helper
    	             ->doctrine2Json
    	             ->encode($result, $this->_shopPersistanceService->getShopEntityManager());
    	
        $this->getResponse()->setHttpResponseCode(400)->appendBody($json);  
    } 

    /**
     * 
     * Insert an entity into the persistance layer
     */
    public function postAction()
    {	
		$result = $this->_shopPersistanceService
		               ->insert(array('name' => 'pino', 'address' => '123', 'zipCode' => '15021', 'tags' => array(1, 2, 3)));
		               
		if (false === $result) {
			
		} 
		
		$json = $this->_helper
    	             ->doctrine2Json
    	             ->encode($result, $this->_shopPersistanceService->getShopEntityManager());		
		
        $this->getResponse()->setHttpResponseCode(200)->appendBody($json);  		
        
		 // Set the HTTP response code to 201, indicating "Created"
    	 // Set the Location header to point to the canonical URI for the newly created item: "/team/31"
    	 // Provide a representation of the newly created item
		// need an action helper with code and messages	
    }
    
    /**
     * 
     * Update an entity into the persistance layer
     */
    public function putAction()
    {
    		
		$result = $this->_shopPersistanceService
		               ->update(array('id' => 100, 'name' => 'aaaa2222', 'address' => '123', 'zipCode' => '10021', 'tags' => array(1, 2, 3)));

		if (false === $result) {
			
		} 		               
		               
		$json = $this->_helper
    	             ->doctrine2Json
    	             ->encode($result, $this->_shopPersistanceService->getShopEntityManager());		
		    	             
        $this->getResponse()->setHttpResponseCode(200)->appendBody($json); 
		// Similarly, with PUT requests, you simply indicate an HTTP 200 status when successful, and show a representation of the updated item. DELETE requests should return an HTTP 204 status (indicating success - no content), with no body content. 
    }
    
    /**
     * 
     * Delete an entity into the persistance layer
     */
    public function deleteAction()
    {
		$result = $this->_shopPersistanceService
		               ->remove(array(12));
		print_r($result);
		die();
    }
    
    
    public function postDispatch() 
    {
    	/*
    	place it into a controller helper!
    	$json = $this->_helper
        		     ->json
        		     ->encodeJson('');
        $this->getResponse()
             ->setHttpResponseCode(400)
             ->appendBody($json);
        */  	
    }
}


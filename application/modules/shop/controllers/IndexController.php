<?php

class Shop_IndexController extends Zend_Controller_Action
{
	
	protected $_serviceModelShop;

	
	protected $_entityManager;
	
	
    public function init()
    {
    	$this->_entityManager = $this->getInvokeArg('bootstrap')->entityManagers['shop'];
    	$this->_serviceModelShop = new Shop_Service_Model($this->_entityManager);
        /* Initialize action controller here */
    }
    
    /**
     * 
     * Retrieves a collection of entities from persistance layer
     */
    public function indexAction()
    {    
    	$params = $this->getRequest()->getQuery();
		$result = $this->_serviceModelShop->fetch($params);
		die();
    }

    /**
     * 
     * Retrieves an entity from the persistance layer
     */
    public function getAction()
    {
		$result = $this->_serviceModelShop->fetchById(100);
		print_r($result);
		die();
    } 

    /**
     * 
     * Insert an entity into the persistance layer
     */
    public function postAction()
    {
		$result = $this->_serviceModelShop->insert(array('name' => 'testing', 'address' => '123', 'zipCode' => '10021', 'tags' => array(1, 2, 3)));
		print_r($result);
		die();
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
		$result = $this->_serviceModelShop->update(array('shopId' => 12, 'name' => 'aaaa', 'address' => '123', 'zipCode' => '10021', 'tags' => array(1, 2, 3)));
		print_r($result);
		die();
		// Similarly, with PUT requests, you simply indicate an HTTP 200 status when successful, and show a representation of the updated item. DELETE requests should return an HTTP 204 status (indicating success - no content), with no body content. 
    }
    
    /**
     * 
     * Delete an entity into the persistance layer
     */
    public function deleteAction()
    {
		$result = $this->_serviceModelShop->remove(array(12));
		print_r($result);
		die();
    }
    
    
    public function postDispatch() 
    {
        // place it into a controller helper!
    	/*$json = $this->_helper
        		     ->json
        		     ->encodeJson('');
        $this->getResponse()
             ->setHttpResponseCode(400)
             ->appendBody($json);*/   	
    }
}


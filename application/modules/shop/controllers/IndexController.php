<?php

class Shop_IndexController extends Zend_Rest_Controller
{
	
	protected $_shopPersistanceService;
	
	protected $_responseBody;
	
	protected $_responseCode;
	
	protected $_responseHeaders = array();
	
	protected $_inputParams = array();
	
    public function init()
    {
    	$config = array();
    	
    	$config['shopEntityManager'] = $this->getInvokeArg('bootstrap')->entityManagers['shop'];
    	                                    
		$config['tagPersistanceService'] = new Tag_Service_Persistance($config);
    	
		$config['shopForm'] = new Shop_Form_Shop($config);
    	
    	$this->_shopPersistanceService = new Shop_Service_Persistance($config);
    }
    
    
    public function preDispatch() 
    {
    	$request = $this->getRequest();
		if (true === $request->isGet()) {
    		$this->_inputParams = $request->getQuery();
    	} else if (true === $request->isPost() || true === $request->isPut()) {
    		$this->_inputParams = Zend_Json::decode($request->getRawBody());
    	}     	
    }
    
    /**
     * 
     * Retrieves a collection of entities from persistance layer
     */
    public function indexAction()
    {        	
    	$this->_responseBody = $this->_shopPersistanceService->fetch($this->_inputParams);
		$this->_responseCode = 200;
    }

    /**
     * 
     * Retrieves an entity from the persistance layer
     */
    public function getAction()
    {
		if (false === ($this->_responseBody = $this->_shopPersistanceService->fetchById($this->_inputParams))) {
			$this->_responseCode = 404;
		} else {
			$this->_responseCode = 200;			
		}
    } 

    /**
     * Insert an entity into the persistance layer
     * array('name' => 'pino', 'address' => '123', 'zipCode' => '15021', 'tags' => array(1, 2, 3))
     */
    public function postAction()
    {	
		$result = $this->_shopPersistanceService->insert($this->_inputParams);
		if (false === $result) {
			$this->_responseCode = 401;
			$this->_responseBody = $this->_shopPersistanceService->getShopForm()->getMessages();	
		} else {
			$this->_responseCode = 201;
			$this->_responseBody = $result;
		}	
    }
    
    /**
     * Update an entity into the persistance layer
     */
    public function putAction()
    {
		$result = $this->_shopPersistanceService->update($this->_inputParams);
    	if (false === $result) {
			$this->_responseCode = 401;
    		$this->_responseBody = $this->_shopPersistanceService->getShopForm()->getMessages();	
		} else {
			$this->_responseCode = 201;
			$this->_responseBody = $result;
		}            
    }
    
    /**
     * Delete an entity into the persistance layer
     */
    public function deleteAction()
    {
		$result = $this->_shopPersistanceService->remove($this->_inputParams);
    }
    
    
    public function postDispatch() 
    {
		if (true === ($this->_responseBody instanceof App\Doctrine\Entity\Shop)) {
	    	$json = $this->_helper->doctrine2Json->encode(
				$this->_responseBody, 
				$this->_shopPersistanceService->getShopEntityManager()
			);
		} else {
			$json = $this->_helper->json->encodeJson($this->_responseBody);
		}
		if (true !== empty($this->_inputParams['callback'])) {
			$json = $this->_inputParams['callback'] . '(' . $json . ');';
		}
        $this->getResponse()->setHttpResponseCode($this->_responseCode)->appendBody($json);
    }
}


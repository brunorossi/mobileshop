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
		try {
    	$config = array();
    	
    	$config['shopEntityManager'] = $this->getInvokeArg('bootstrap')->entityManagers['shop'];

		$config['tagPersistanceService'] = new Tag_Service_Persistance($config);

    	    	    	     	
		$config['shopForm'] = new Shop_Form_Shop($config);
	
    	$this->_shopPersistanceService = new Shop_Service_Persistance($config);
		} catch (Exception $e) {
			echo $e->getMessage();
			die();
		}

    	
    }
    
    
    public function preDispatch() 
    {
    	// do a zend controller helper to get all params
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

    	
    	$entityManager = $this->getInvokeArg('bootstrap')
    	                      ->entityManagers['shop'];
    	
    	$serializer = new Bgy\Doctrine\EntitySerializer($entityManager);
    	
    	$json = $this->_helper->doctrineToJson->encodeJson(
    		$this->_responseBody,
    		$serializer,
    		array('App\Doctrine\Entity\Shop')
		);
        
		if (isset($this->_inputParams['callback'])) {
			$json = $this->_inputParams['callback'] . '(' . $json . ');';
		}
		
		$this->getResponse()
        	 ->setHttpResponseCode($this->_responseCode)
        	 ->appendBody($json);
    }
}


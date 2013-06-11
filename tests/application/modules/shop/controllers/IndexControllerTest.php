<?php

class Shop_IndexControllerTest extends Zend_Test_PHPUnit_ControllerTestCase
{
	
	public function setUp()
    {
        $this->bootstrap = new Zend_Application(APPLICATION_ENV, APPLICATION_PATH . '/configs/application.ini');
        parent::setUp();
    }
    
    public function testIndexAction()
    {
        $this->dispatch('shop');
     	$this->assertModule('shop');
        $this->assertController('index');
        $this->assertAction('index');
        $this->assertHeaderContains('Content-Type', 'application/json');
        $this->assertResponseCode(200);
    }
    
    public function testIndexActionWithJsonp()
    {
        $this->dispatch('/shop/index/index/jsonp/?callback=test');
        $this->assertModule('shop');
        $this->assertController('index');
        $this->assertAction('index');
        $this->assertHeaderContains('Content-Type', 'application/json');
        $this->assertResponseCode(200);
    }
    
    public function testGetAction()
    {
        $this->dispatch('shop/index/?id=1');
        $this->assertModule('shop');
        $this->assertController('index');
        $this->assertAction('get');
        $this->assertHeaderContains('Content-Type', 'application/json');
        $this->assertResponseCode(200);
    }
    
    public function testGetActionWithJsonp()
    {
        $this->dispatch('/shop/index/get/jsonp/?id=1&callback=test');
        $this->assertModule('shop');
        $this->assertController('index');
        $this->assertAction('get');
        $this->assertHeaderContains('Content-Type', 'application/json');
        $this->assertResponseCode(200);
    }
    
    // curl -i -X POST -d '{"name": "pino"}' http://mobileshop.zs.vbox/shop
    public function testPostAction() 
    {
    	$json = '{"name": "pino", "address": "123", "zipCode": "15021", "tags": [1, 2, 3]}';
    	$this->getRequest()->setMethod('POST')->setRawBody($json);
    	$this->dispatch('/shop');
        $this->assertModule('shop');
        $this->assertController('index');
        $this->assertAction('post');
        $this->assertHeaderContains('Content-Type', 'application/json');
        $this->assertResponseCode(201);
    }

    // curl -i -X PUT -d '{"name": "pino"}' http://mobileshop.zs.vbox/shop
    public function testPutAction() 
    {
    	$json = '{"id": 15, "name": "pino", "address": "123", "zipCode": "15021", "tags": [1, 2, 3]}';
    	$this->getRequest()->setMethod('PUT')->setRawBody($json);
    	$this->dispatch('/shop');
        $this->assertModule('shop');
        $this->assertController('index');
        $this->assertAction('put');
        $this->assertHeaderContains('Content-Type', 'application/json');
        $this->assertResponseCode(201);
    }    
}
<?php
class IndexController extends Zend_Controller_Action
{

    public function init()
    {
    }

    public function indexAction()
    {
			try {
				
				$em = $this->getInvokeArg('bootstrap')->entityManagers['shop'];
		    	// with namespace: new App\Doctrine\Repository\Shop
		    	$shop = new App\Doctrine\Entity\Shop();
		    	$shop->setAddress('Address');
		    	$shop->setName('Name');
		    	$shop->setZipCode('12345');
		    	$em->persist($shop);
				$em->flush();    	
		
				
				// with namespace: $em->getRepository('App\Doctrine\Repository\Shop')
				foreach ($em->getRepository('App\Doctrine\Entity\Shop')->findAll() as $shop) {
					echo $shop->getAddress() . '<br />';
				}
				
			} catch (Exception $e) {
			    echo $e->getMessage();
			}

    }

}


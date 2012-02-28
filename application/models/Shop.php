<?php
use Doctrine\Common\Collections\ArrayCollection;

class Service_Model_Shop
{
	
	protected $_entityManager;
	
	
	public function __construct($entityManager) {
		$this->_entityManager = $entityManager;
	}
	
	
	public function save(array $shop) {
		$shop = new App\Doctrine\Entity\Shop($shop);
		$this->_entityManager->persist($shop);
		$this->_entityManager->flush();  			
		return $shop;
	}
	
	
	public function update($shopId, array $shop) {
		if (null !== ($shop = $this->_entityManager->getReference('App\Doctrine\Entity\Shop', $shopId))) {
			$shop->init($shop);			
			$this->_entityManager->persist($shop);
			$this->_entityManager->flush();  
		}
	}

}
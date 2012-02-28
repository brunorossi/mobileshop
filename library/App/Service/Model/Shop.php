<?php
use Doctrine\Common\Collections\ArrayCollection;
use App\Doctrine\Entity\Shop;

class App_Service_Model_Shop
{
	
	protected $_shopEntityManager;
	
	
	public function __construct($entityManager) {
		$this->_shopEntityManager = $entityManager;
	}
	
	

	
	/**
	 * 
	 * Enter description here ...
	 * @param array $values
	 */
	public function insert(array $values) {
		
		$values['tags'] = $this->_shopEntityManager
							   ->getRepository('App\Doctrine\Entity\Tag')
							   ->getTagsCollectionByTagIds($values['tags']);
		
		$shop = new App\Doctrine\Entity\Shop($values);
		
		$this->_shopEntityManager->persist($shop);	
		
     	$this->_shopEntityManager->flush();
        
		return $shop;
	
	}
	
	/**
	 * 
	 * Enter description here ...
	 * @param integer $shopId
	 * @param array $values
	 */
	public function update($shopId, array $values) {
		
		if (null === ($shop = $this->_shopEntityManager->find('App\Doctrine\Entity\Shop', $shopId))) {
			return null;
		}
		
		$shop->getTags()->clear();	
		
		$values['tags'] = $this->_shopEntityManager
							   ->getRepository('App\Doctrine\Entity\Tag')
							   ->getTagsCollectionByTagIds($values['tags']);		
		
		$shop->init($values);										
		
		$this->_shopEntityManager->persist($shop);
		
     	$this->_shopEntityManager->flush();		
		
		return $shop;
	
	}
	
	/**
	 * 
	 * Enter description here ...
	 * @param array $shopIds
	 */
	public function remove(array $shopIds) {
		
		foreach ($shopIds as $shopId) {

			$shop = $this->_shopEntityManager->getReference('App\Doctrine\Entity\Shop', $shopId);
			
			$this->_shopEntityManager->remove($shop);
		
		}
		
		$this->_shopEntityManager->flush();	
		
	}
	
}
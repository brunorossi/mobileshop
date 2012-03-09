<?php
use Doctrine\Common\Collections\ArrayCollection;
use App\Doctrine\Entity\Shop;

class Shop_Service_Model
{
	
	protected $_shopEntityManager;
	
	protected $_inputFilter;
	
	public function __construct($entityManager) {
		$this->_shopEntityManager = $entityManager;
	}

	public function setInputFilter(App_Validate_Entity_Shop $meta, $values = null) {
		// $values === null and $meta->getValues === isempty exception
		// $values sovrascrive i values dei meta
		$this->_inputFilter = new Zend_Filter_Input(
							  	  $meta->getFilters(), 
								  $meta->getValidators(), 
								  $meta->getValues()
							  );		
		return $this->_inputFilter;
	}

	public function getInputFilter() {
		// lazy loading, change $_inputFilter into array
		return $this->_inputFilter;
	}
	
	/**
	 * 
	 * Enter description here ...
	 * @param array $criteria
	 */
	public function fetch(array $criterias) {
		$result = $this->_shopEntityManager
			        ->getRepository('App\Doctrine\Entity\Shop')
			        ->findBy($criterias);
		return $result;
	}
	
	/**
	 * 
	 * Enter description here ...
	 * @param unknown_type $shopId
	 */
	public function fetchById($shopId) {
		return $this->_shopEntityManager
			        ->find('App\Doctrine\Entity\Shop', $shopId);		
	}
	
	/**
	 * 
	 * Enter description here ...
	 * @param array $values
	 */
	public function insert(array $values) {
		
		try {
			
			$meta = new App_Validate_Entity_Shop(array('values' => $values));
			
			$meta->removeValidator('shopId');
			
            if (false === $this->setInputFilter($meta)->isValid()) {
            	
            	return false;
            	
            }
            
            $escaped = $this->getInputFilter()->getEscaped();
            	
			$escaped['tags'] = $this->_shopEntityManager
							        ->getRepository('App\Doctrine\Entity\Tag')
							        ->getTagsCollectionByTagIds($escaped['tags']);
			
			$shop = new App\Doctrine\Entity\Shop($escaped);
			
			$this->_shopEntityManager->persist($shop);	
		
			$this->_shopEntityManager->flush();   
		
			return true;
		
		} catch (Exception $e) {
			
			return false;
			
		}
     	
	}
	
	/**
	 * 
	 * Enter description here ...
	 * @param integer $shopId
	 * @param array $values
	 */
	public function update(array $values) {
		
		try {
			
			$meta = new App_Validate_Entity_Shop(array('values' => $values));
			
            if (false === $this->setInputFilter($meta)->isValid()) {
            	
            	return false;
            	
            }		

            $escaped = $this->getInputFilter()->getEscaped();
			
			if (null === ($shop = $this->fetchById($escaped['shopId']))) {
				
				return false;
			
			}
			
			$shop->getTags()->clear();	
			
			$escaped['tags'] = $this->_shopEntityManager
								    ->getRepository('App\Doctrine\Entity\Tag')
								    ->getTagsCollectionByTagIds($escaped['tags']);		
			
			unset($escaped['shopId']);					    
								    
			$shop->init($escaped);										
			
			$this->_shopEntityManager->persist($shop);
			
	     	$this->_shopEntityManager->flush();		
	     	
	     	return true;
		
		} catch (Exception $e) {
			
			return false;
			
		}
		
	}
	
	/**
	 * 
	 * Enter description here ...
	 * @param array $shopIds
	 */
	public function remove(array $shopIds) {
		
		try {
		
			foreach ($shopIds as $shopId) {	
				
				$shop = $this->_shopEntityManager->getReference('App\Doctrine\Entity\Shop', $shopId);
				
				$this->_shopEntityManager->remove($shop);
			
			}
			
			$this->_shopEntityManager->flush();	
			
			return true;
			
		} catch (Exception $e) {
			
			return false;
			
		}
		
	}

}
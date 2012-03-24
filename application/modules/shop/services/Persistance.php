<?php
use Doctrine\Common\Collections\ArrayCollection;
use App\Doctrine\Entity\Shop;

class Shop_Service_Persistance
{
	/**
	 * The Doctrine Shop Entity Manager
	 * @var App_Resource_Doctrine 
	 */
	protected $_shopEntityManager;
	
	/**
	 * The Shop_Form_Shop object used for filtering and validation
	 * @var Shop_Form_Shop
	 */
	protected $_shopForm;
	
	/**
	 * The Tag_Service_Persistance Service
	 * 
	 * @var Tag_Service_Persistance
	 */
	protected $_tagPersistanceService;
	
	/**
	 * The unified constructor
	 * 
	 * @param array|null $options
	 */
	public function __construct (array $options = null) 
	{
		if (null !== $options) {
			
			foreach ($options as $key => $value) {
			
				$methodName = 'set' . ucfirst($key);
				
				if (false === method_exists($this, $methodName)) {
					
					throw new Exception("The method $methodName does not exist.");		
				
				}
				
				$this->$methodName($value);				
			}
		}
	}	
	
	/**
	 * @return the $_tagPersistanceService
	 */
	public function getTagPersistanceService() 
	{
		return $this->_tagPersistanceService;
	}

	/**
	 * @param Tag_Service_Persistance $_tagPersistanceService
	 */
	public function setTagPersistanceService(Tag_Service_Persistance $tagPersistanceService) 
	{
		$this->_tagPersistanceService = $tagPersistanceService;
	}
	
	/**
	 * @return the $_shopEntityManager
	 */
	public function getShopEntityManager() 
	{
		return $this->_shopEntityManager;
	}

	/**
	 * @return the $_shopForm
	 */
	public function getShopForm() 
	{
		return $this->_shopForm;
	}

	/**
	 * @param App_Resource_Doctrine $shopEntityManager
	 */
	public function setShopEntityManager(Doctrine\ORM\EntityManager $shopEntityManager) 
	{
		$this->_shopEntityManager = $shopEntityManager;
	}

	/**
	 * @param Shop_Form_Shop $shopForm
	 */
	public function setShopForm(Shop_Form_Shop $shopForm) 
	{
		$this->_shopForm = $shopForm;
	}

	/**
	 * Retrieves all the Shops Doctrine Entities from the persistance layer in according with search criterias 
	 * If the param $criteria is an empty array it will returns all shops
	 *
	 * @param array $criteria
	 */
	public function fetch(array $criterias) 
	{

		$this->_shopForm->setContext('fetchAll')->applyContext();
		
		$safeInput = array('search' => null, 
						   'order' => array('field' => null, 'type' => 'ASC'),
						   'limit' => null,
						   'offset' => null);
		
		$safeInput = array_merge($safeInput, $this->_shopForm->getValidValues($criterias));	

		$queryBuilder = $this->_shopEntityManager
					   ->createQueryBuilder()
					   ->select(array('shop')) 
					   ->from('App\Doctrine\Entity\Shop', 'shop')
					   ->innerJoin('shop.tags', 'tag');

		if (null !== $safeInput['search']) {

			foreach ($safeInput['search'] as $field => $value) {
				
				if ('tagName' === $field) {
					$field = 'tag.name';
				} else {
					$field = 'shop.' . $field;
				}

				$queryBuilder->where($queryBuilder->expr()->like($field, ':value'));
				$queryBuilder->setParameter('value', $value);
			
			}
		
		}

		if (null !== $safeInput['order']['field']) {
			$queryBuilder->orderBy('shop.' . $safeInput['order']['field'], $safeInput['order']['type']);
		}

		if (null !== $safeInput['offset']) {
			$queryBuilder->setFirstResult($safeInput['offset']);
		}

		if (null !== $safeInput['limit']) {
			$queryBuilder->setMaxResults($safeInput['limit']);
		}	
		
		return $queryBuilder->getQuery()->getResult();

	}
	
	/**
	 * Retrieves the Shop Doctrine Entity identified by the $shopId
	 *  
	 * @param int $shopId
	 */
	public function fetchById(array $criterias) 
	{
		
		$this->_shopForm->setContext('fetchOne')->applyContext();	

		if (false === $this->_shopForm->isValid($criterias)) {
            	
        	return false;
            	
        }
        
		return $this->_shopEntityManager
		            ->find('App\Doctrine\Entity\Shop', $this->_shopForm->getValue('id'));
	
	}
	
	/**
	 * Persists a new Shop Doctrine Entity  
	 * 
	 * @param array $values
	 */
	public function insert(array $values) 
	{
		
		$this->_shopForm->setContext('insert')->applyContext();
			
		if (false === $this->_shopForm->isValid($values)) {
            	
        	return false;
            	
        }
            
        $escaped = $this->_shopForm->getValues();
            	
		$escaped['tags'] = $this->_tagPersistanceService
							    ->getTagsCollectionByTagIds($escaped['tags']);
			
		$shop = new App\Doctrine\Entity\Shop($escaped);
			
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
	public function update(array $values) {
		
		if (false === $this->_shopForm->isValid($values)) {
            	
        	return false;
            	
		}
            
        $escaped = $this->_shopForm->getValues();
			
		$shop = $this->_shopEntityManager->find('App\Doctrine\Entity\Shop', $escaped['id']);
			
		$shop->getTags()->clear();	
			
		$escaped['tags'] = $this->_tagPersistanceService
								->getTagsCollectionByTagIds($escaped['tags']);		
			
		unset($escaped['id']);					    
								    
		$shop->init($escaped);										
			
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
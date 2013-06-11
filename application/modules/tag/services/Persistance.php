<?php
use Doctrine\Common\Collections\ArrayCollection;
use App\Doctrine\Entity\Tag;

class Tag_Service_Persistance
{
	/**
	 * The Doctrine Shop Entity Manager
	 * @var App_Resource_Doctrine 
	 */
	protected $_shopEntityManager;
	
	/**
	 * The Tag_Form_Tag object used for filtering and validation
	 * @var Tag_Form_Tag
	 */
	protected $_tagForm;
	
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
	public function setShopForm(Tag_Form_Tag $tagForm) 
	{
		$this->_tagForm = $tagForm;
	}

	/**
	 * Retrieves all the Shops Doctrine Entities from the persistance layer in according with search criterias 
	 * If the param $criteria is an empty array it will returns all shops
	 *
	 * @param array $criteria
	 */
	public function fetch(array $criterias) 
	{
		$result = $this->_shopEntityManager
			           ->getRepository('App\Doctrine\Entity\Tag')
			           ->findBy($criterias);
		return $result;
	}
	
	/**
	 * Retrieves the Shop Doctrine Entity identified by the $shopId
	 *  
	 * @param int $shopId
	 */
	public function fetchById($tagId) 
	{
		return $this->_shopEntityManager
			        ->find('App\Doctrine\Entity\Tag', $tagId);		
	}
	
	/**
	 * Persists a new Shop Doctrine Entity  
	 * 
	 * @param array $values
	 */
	public function insert(array $values) 
	{
		
		try {
			
			$this->_shopForm->setContext();
			
			if (false === $this->_shopForm->isValid($values)) {
            	
            	return false;
            	
            }
            
            $escaped = $this->_shopForm->getValues();
            	
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
			
			$tags = $this->_shopEntityManager 
				 		 ->getRepository('App\Doctrine\Entity\Tag') 
				 		 ->findAll();
			
			$tagsElementOptions = array();
			foreach ($tags as $tag) {
				$tagsElementOptions[$tag->getTagId()] = $tag->getName();
			} 
			
			$config = new Zend_Config_Ini(APPLICATION_PATH . '/modules/shop/configs/forms.ini', 'update');
			$form = new Zend_Form($config);
			$form->getElement('tags')->setMultiOptions($tagsElementOptions);
			
			
			if (false === $form->isValid($values)) {
            	
            	return false;
            	
            }
            
            $escaped = $form->getValues();
			
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
			
			
			print_r($e->getMessage());
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
	
	public function getTagsCollectionByTagIds(array $tagIds) 
	{
		$tags = $this->_shopEntityManager
					->getRepository('App\Doctrine\Entity\Tag')
					->getTagsByTagIds($tagIds);
		return new ArrayCollection($tags);
	}

}
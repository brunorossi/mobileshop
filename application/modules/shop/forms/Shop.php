<?php

class Shop_Form_Shop extends Zend_Form 
{

	protected $_tagPersistanceService;
	
	protected $_context;
	
	/**
	 * @return the $_tagPersistanceService
	 */
	public function getTagPersistanceService() 
	{
		return $this->_tagPersistanceService;
	}

	/**
	 * @return the $_context
	 */
	public function getContext()
	{
		return $this->_context;
	}

	/**
	 * @param field_type $_context
	 */
	public function setContext($context) 
	{
		$this->_context = $context;
		return $this;
	}

	/**
	 * @param field_type $_tagPersistanceService
	 */
	public function setTagPersistanceService(Tag_Service_Persistance $tagPersistanceService) 
	{
		$this->_tagPersistanceService = $tagPersistanceService;
		return $this;
	}	
	
	/**
	 * 
	 * Enter description here ...
	 */
	public function applyContext()
	{
		$methodName = '_apply' . ucfirst($this->_context) . 'Context'; 
		if (false === method_exists($this, $methodName)) {
			throw new Exception("The method name $methodName does not exist.");
		}
		$this->$methodName();		
	}
	
	public function init() 
	{	
		$tagsElementOptions = array();
		

		foreach ($this->_tagPersistanceService->fetch(array()) as $tag) {
			$tagsElementOptions[$tag->getTagId()] = $tag->getName();
		} 		
				
		
		$shopIdElement = new Zend_Form_Element_Text('id');
		$shopIdElement->setRequired(true)
					  ->addValidator(new Zend_Validate_Digits());
		$this->addElement($shopIdElement);					  	
		
		$nameElement = new Zend_Form_Element_Text('name');
		$nameElement->setRequired(true)
					->addValidator(new Zend_Validate_StringLength(array('max' => 500)));
		$this->addElement($nameElement);
					
		$addressElement = new Zend_Form_Element_Text('address');
		$addressElement->setRequired(true)
					   ->addValidator(new Zend_Validate_StringLength(array('max' => 500)));		
		$this->addElement($addressElement);
					   
		$addressElement = new Zend_Form_Element_Text('zipCode');
		$addressElement->setRequired(true)
					   ->addValidator(new Zend_Validate_PostCode(array('format' => '\d{5}')));
		$this->addElement($addressElement);	

		$tagsElement = new Zend_Form_Element_Multiselect('tags');
		$tagsElement->setRequired(true)	
					->setMultiOptions($tagsElementOptions);
		$this->addElement($tagsElement);				 
		
		$this->setElementFilters(
			array(
				new Zend_Filter_StringTrim(),
				new Zend_Filter_StripTags(),
			)
		);
	}

	protected function _applyInsertContext()
	{
		$this->removeElement('id');
	}
	
	protected function _applyFetchOneContext()
	{ 
		foreach ($this->getElements() as $element) {
			if ('id' !== $element->getName()) {
				$this->removeElement($element->getName());
			}
		}
	}

	protected function _applyFetchAllContext()
	{ 
		$this->removeElement('id');
		$this->removeElement('tags');
		
		foreach ($this->getElements() as $element) {
			$element->clearValidators()->setBelongsTo('search');
		}
		
		$element = new Zend_Form_Element_Text('tagName');
		$element->setBelongsTo('search');
		$this->addElement($element);			
		
		$orderTypeElement = new Zend_Form_Element_Select('type');
		$orderTypeElement->setBelongsTo('order')
						 ->setMultiOptions(array('ASC' => 'Ascendent', 
												 'DESC' => 'Descendent'));
		$this->addElement($orderTypeElement);

		$orderFieldElement = new Zend_Form_Element_Select('field');
		$orderFieldElement->setBelongsTo('order')
						  ->setMultiOptions(array('name' => 'Name', 
												  'address' => 'Address',
												  'zipCode' => 'Zip',
						  						  'tags' => 'Tag Name'));
		$this->addElement($orderFieldElement);
		
		$offsetElement = new Zend_Form_Element_Text('offset');
		$offsetElement->addValidator(new Zend_Validate_Digits());
		$this->addElement($offsetElement);
		
		$offsetElement = new Zend_Form_Element_Text('limit');
		$offsetElement->addValidator(new Zend_Validate_Digits());
		$this->addElement($offsetElement);		
	}
}
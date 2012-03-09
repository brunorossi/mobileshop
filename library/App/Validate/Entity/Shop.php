<?php
/**
 * Enter description here ...
 * @author bruno
 */
class App_Validate_Entity_Shop extends App_Validate_Entity
{
	
	/**
	 * Enter description here ...
	 */
	public function init() 
	{
		
		$this->setValidators(
			array(
                'shopId' => array(
                    'digits',
                    'presence' => 'required',
                ),		
                'nameLength' => array(
                    new Zend_Validate_StringLength(array('max' => 500)),
                 	'fields' => 'name',
                ),
                'nameRequired' => array(
                    'presence' => true,
                 	'fields' => 'name',
                ),                
                'address' => array(
                    new Zend_Validate_StringLength(array('max' => 500)),
                    'presence' => 'required',
                ),
                'zipCode' => array(
                    new Zend_Validate_Regex(array('pattern' => '/^[\d]{5}/')),
                    'presence' => 'required',
                ),
                'tags' => array(
                    'presence' => 'required',
                ),
			)
		);		
		
		$this->setFilters(array('*' => array('StringTrim')));
	}
	
}
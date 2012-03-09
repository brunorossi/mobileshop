<?php
/**
 * Enter description here ...
 * @author bruno
 */
class App_Validate_Entity
{
	/**
	 * 
	 */
	protected $_values = array();
	
	/**
	 * 
	 * Enter description here ...
	 * @var unknown_type
	 */
	protected $_validators = array();
	
	/**
	 * 
	 * Enter description here ...
	 * @var unknown_type
	 */
	protected $_filters = array();
	
	/**
	 * 
	 * Enter description here ...
	 * @var unknown_type
	 */
	protected $_entityManager = null;
	
	/**
	 * 
	 * Enter description here ...
	 * @param array $options
	 */
	public function __construct(array $options = null) 
	{
		
		if (null !== $options) {
            $this->setOptions($options);			
		}
		
		// extensions
		$this->init();
	
	}
	
	/**
	 * 
	 * Enter description here ...
	 * @param array $options
	 */
	public function setOptions(array $options) 
	{
		foreach ($options as $methodName => $value) {
			
			$method = 'set' . ucfirst($methodName);
			
			if (true === method_exists($this, $method)) {
				$this->$method($value);
			}
		
		}
	}
	
	/**
	 * 
	 * Enter description here ...
	 */
	public function init() 
	{}
	
	/**
	 * 
	 * Enter description here ...
	 * @param array $values
	 */
	public function setValidators(array $values) 
	{
		$this->_validators = $values;
	} 	
	
	/**
	 * 
	 * Enter description here ...
	 * @param unknown_type $index
	 * @param array $value
	 */
	public function addValidator($index, array $value) 
	{
		$this->_validators[$index] = $value;
	} 
	
	/**
	 * 
	 * Enter description here ...
	 * @param unknown_type $index
	 */
	public function removeValidator($index) 
	{
		unset($this->_validators[$index]);
	}
	
	/**
	 * 
	 * Enter description here ...
	 * @param array $values
	 */
	public function setValues(array $values) 
	{
		$this->_values = $values;
	} 	
	
	/**
	 * 
	 * Enter description here ...
	 * @param unknown_type $index
	 * @param array $value
	 */
	public function addValue($index, array $value) 
	{
		$this->_values[$index] = $value;
	} 
	
	/**
	 * 
	 * Enter description here ...
	 * @param unknown_type $index
	 */
	public function removeValue($index) 
	{
		unset($this->_values[$index]);
	}
		
	/**
	 * 
	 * Enter description here ...
	 * @param array $values
	 */
	public function setFilters(array $values) 
	{
		$this->_filters = $values;
	} 	
	
	/**
	 * @param field_type $_entityManager
	 */
	public function setEntityManager($_entityManager) 
	{
		$this->_entityManager = $_entityManager;
	}
	
	/**
	 * 
	 * Enter description here ...
	 * @param unknown_type $index
	 * @param array $value
	 */
	public function addFilter($index, array $value) 
	{
		$this->_filters[$index] = $value;
	} 
	
	/**
	 * 
	 * Enter description here ...
	 * @param unknown_type $index
	 */
	public function removeFilter($index) 
	{
		unset($this->_filters[$index]);
	}
	
	/**
	 * 
	 * Enter description here ...
	 */
	public function getValidators() 
	{
		return $this->_validators;
	} 
	
	/**
	 * 
	 * Enter description here ...
	 */
	public function getFilters() 
	{
		return $this->_filters;
	}

	/**
	 * Enter description here ...
	 */
	public function getValues()
	{
		return $this->_values;
	}
	
	/**
	 * 
	 * Enter description here ...
	 * @param unknown_type $index
	 */
	public function getValidator($index) 
	{
		return $this->_validators[$index];
	} 
	
	/**
	 * 
	 * Enter description here ...
	 * @param unknown_type $index
	 */
	public function getFilter($index) 
	{
		return $this->_filters[$index];
	}
	
	/**
	 * 
	 * Enter description here ...
	 * @param unknown_type $index
	 */
	public function getValue($index) 
	{
		return $this->_values[$index];
	}
	
	/**
	 * @return the $_entityManager
	 */
	public function getEntityManager() 
	{
		return $this->_entityManager;
	}
	
	/**
	 * Enter description here ...
	 * @param unknown_type $index
	 */
	public function valueExists($index) 
	{
		return isset($this->_values[$index]);
	}
}

<?php
class App_Validate_Doctrine_EntityExists extends Zend_Validate_Abstract
{
    /**
     * @var the entity manager
     */
    protected $_entityManager = null;
    
    /**
     * (non-PHPdoc)
     * @see Zend_Validate_Interface::isValid()
     */
    public function isValid ($value)
    {}
} 
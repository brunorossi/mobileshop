<?php
/**
 * Zend Framework
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://framework.zend.com/license/new-bsd
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@zend.com so we can send you a copy immediately.
 *
 * @category   Zend
 * @package    Zend_Controller
 * @subpackage Zend_Controller_Action_Helper
 * @copyright  Copyright (c) 2005-2011 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @version    $Id: Json.php 23775 2011-03-01 17:25:24Z ralph $
 */

/**
 * @see Zend_Controller_Action_Helper_Abstract
 */
require_once 'Zend/Controller/Action/Helper/Abstract.php';

/**
 * Simplify AJAX context switching based on requested format
 *
 * @uses       Zend_Controller_Action_Helper_Abstract
 * @category   Zend
 * @package    Zend_Controller
 * @subpackage Zend_Controller_Action_Helper
 * @copyright  Copyright (c) 2005-2011 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
class App_Controller_Action_Helper_DoctrineToJson extends Zend_Controller_Action_Helper_Json
{

	protected function _encodeDoctrineEntity(
		$data, Bgy\Doctrine\EntitySerializer $serializer, array $entityClasses
	) {
		
		foreach ($entityClasses as $entityClass) {
			
			if (true === ($data instanceof $entityClass)) { 
				
				return $serializer->toArray($data);
			
			}	
		
		}			
	
	}
	
    public function encodeJson(
    	$data, Bgy\Doctrine\EntitySerializer $serializer, array $entityClasses, $keepLayouts = false
    ) {
    	
    	$serializedEntity = array();
    	
		if (true === is_array($data)) {
			foreach ($data as $k => $row) {
				if (null !== ($result = $this->_encodeDoctrineEntity($row, $serializer, $entityClasses))) {
					$serializedEntity[] = $result; 	
				}
			}			
		} else {
			$serializedEntity = $this->_encodeDoctrineEntity($data, $serializer, $entityClasses);	
		}
		
		if (empty($serializedEntity)) {
			$serializedEntity = $data;
		}
		
        /**
         * @see Zend_View_Helper_Json
         */
		return parent::encodeJson($serializedEntity, $keepLayouts);
    }

    /**
     * Encode JSON response and immediately send
     *
     * @param  mixed   $data
     * @param  boolean|array $keepLayouts
     * NOTE:   if boolean, establish $keepLayouts to true|false
     *         if array, admit params for Zend_Json::encode as enableJsonExprFinder=>true|false
     *         if $keepLayouts and parmas for Zend_Json::encode are required
     *         then, the array can contains a 'keepLayout'=>true|false
     *         that will not be passed to Zend_Json::encode method but will be passed
     *         to Zend_View_Helper_Json
     * @return string|void
     */
    public function send($data, $entityManager, $keepLayouts = false)
    {
        $data = $this->encode($data, $entityManager, $keepLayouts);
        
        $response = $this->getResponse();
        
        $response->setBody($data);

        if (false === $this->suppressExit) {
            $response->sendResponse();
            exit;
        }

        return $data;
    }

    /**
     * Strategy pattern: call helper as helper broker method
     *
     * Allows encoding JSON. If $sendNow is true, immediately sends JSON
     * response.
     *
     * @param  mixed   $data
     * @param  boolean $sendNow
     * @param  boolean $keepLayouts
     * @return string|void
     */
    public function direct($data, $entityManager, $sendNow = true, $keepLayouts = false)
    {
        if ($sendNow) {
            return $this->send($data, $entityManager, $keepLayouts);
        }
        return $this->encode($data, $entityManager, $keepLayouts);
    }
}

<?php
namespace App\Doctrine\Entity;

use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Doctrine\Repository\Shop")
 * @ORM\Table(name="shops")
 */
class Shop
{
	
    /**
     * @ORM\Id 
     * @ORM\Column(name="shop_id", type="integer")
     * @ORM\GeneratedValue
     */
    protected $shopId;

    /**
     * @ORM\Column(name="name", type="string", length=500, nullable=false)
     */
    protected $name;

    /**
     * @ORM\Column(name="address", type="string", length=500, nullable=false)
     */
    protected $address;

     /**
      * @ORM\Column(name="zip_code", type="string", length=5, nullable=false)
      */
    protected $zipCode;
    
    /**
     * @var datetime $created
     *
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(type="datetime")
     */
    protected $created;

    /**
     * @var datetime $updated
     * 
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(type="datetime")
     */
    protected $updated;
    
	/**
	 * @return the $shopId
	 */
	public function getShopId() {
		return $this->shopId;
	}

	/**
	 * @return the $name
	 */
	public function getName() {
		return $this->name;
	}

	/**
	 * @return the $address
	 */
	public function getAddress() {
		return $this->address;
	}

	/**
	 * @return the $zipCode
	 */
	public function getZipCode() {
		return $this->zipCode;
	}
	
	/**
	 * @param field_type $name
	 */
	public function setName($name) {
		$this->name = $name;
	}

	/**
	 * @param field_type $address
	 */
	public function setAddress($address) {
		$this->address = $address;
	}

	/**
	 * @param field_type $zipCode
	 */
	public function setZipCode($zipCode) {
		$this->zipCode = $zipCode;
	}

}
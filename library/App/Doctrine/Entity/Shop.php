<?php
/**
 * @Entity(repositoryClass="App\Doctrine\Repository\Shop")
 * @Table(name="shops")
 */
class App_Doctrine_Entity_Shop
{
    /**
     * @Id 
     * @Column(name="shop_id", type="integer")
     * @GeneratedValue
     */
    protected $shopId;

    /**
     * @Column(name="name", type="string", length=500, nullable=false)
     */
    protected $name;

    /**
     * @Column(name="address", type="string", length=500, nullable=false)
     */
    protected $address;

     /**
      * @Column(name="zip_code", type="string", length=5, nullable=false)
      */
    protected $zipCode;
    
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
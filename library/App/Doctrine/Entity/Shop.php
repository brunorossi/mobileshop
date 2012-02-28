<?php
namespace App\Doctrine\Entity;

use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

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
     * @ORM\Column(name="created", type="datetime")
     */
    protected $created;
    
    /**
     * @var datetime $updated
     * 
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(name="updated", type="datetime")
     */
    protected $updated;
    
    /**
     * @Gedmo\Slug(fields={"name"})
     * @ORM\Column(name="slug", type="string", length=500, nullable=false, unique=true)
     */
    protected $slug;
    
	/**
	 * @ORM\ManyToMany(targetEntity="Tag", cascade={"persist"})
	 * @ORM\JoinTable(
	 *     name="shops_tags",
	 * 	   joinColumns={@ORM\JoinColumn(name="shop_id", referencedColumnName="shop_id")},
	 *	   inverseJoinColumns={@ORM\JoinColumn(name="tag_id", referencedColumnName="tag_id")}
	 * )
	 */
    protected $tags;
    
    /**
     * 
     */
	public function __construct(array $options = null) {
		$this->tags = new ArrayCollection;
		if (null !== $options) {
			$this->init($options);
		}
	}
	
	/**
	 * 
	 */
	public function init(array $options) {
		foreach ($options as $name => $value) {
			$method = 'set' . ucfirst($name);
			$this->$method($value);
		}
	}
	    
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
	 * @return the $created
	 */
	public function getCreated() {
		return $this->created;
	}

	/**
	 * @return the $updated
	 */
	public function getUpdated() {
		return $this->updated;
	}

	/**
	 * @return the $seoId
	 */
	public function getSlug() {
		return $this->slug;
	}
	
	/**
	 * @return the $tags
	 */
	public function getTags() {
		return $this->tags;
	}	

	/**
	 * @param fieldtype $name
	 */
	public function setName($name) {
		$this->name = $name;
	}

	/**
	 * @param fieldtype $address
	 */
	public function setAddress($address) {
		$this->address = $address;
	}

	/**
	 * @param fieldtype $zipCode
	 */
	public function setZipCode($zipCode) {
		$this->zipCode = $zipCode;
	}
	
	/**
	 * @param fieldtype $tags
	 */
	public function setTags(ArrayCollection $tags) {
		$this->tags = $tags;
	}
}
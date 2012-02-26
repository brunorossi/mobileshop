<?php
namespace App\Doctrine\Entity;

use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="tags")
 * @ORM\Entity(repositoryClass="App\Doctrine\Repository\Tag")
 * @Gedmo\TranslationEntity(class="App\Doctrine\Entity\TagTranslation")
 */
class Tag
{
    /**
     * @ORM\Id 
     * @ORM\Column(name="tag_id", type="integer")
     * @ORM\GeneratedValue
     */
    protected $tagId;

    /**
     * @Gedmo\Translatable
     * @ORM\Column(name="name", type="string", length=500, nullable=false)
     */
    protected $name;

    /**
     * @Gedmo\Locale
     */
    protected $locale;
    
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
     * @Gedmo\Translatable
     * @ORM\Column(name="slug", type="string", length=500, nullable=false, unique=true)
     */
    protected $slug;
    
	/**
	 * @return the $tagId
	 */
	public function getTagId() {
		return $this->tagId;
	}

	/**
	 * @return the $name
	 */
	public function getName() {
		return $this->name;
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
	 * @return the $slug
	 */
	public function getSlug() {
		return $this->slug;
	}	
	
	/**
	 * @param field_type $name
	 */
	public function setName($name) {
		$this->name = $name;
	}

	/**
	 * @param field_type $locale
	 */
	public function setTranslatableLocale($locale) {
		$this->locale = $locale;
	}
}
<?php
namespace App\Doctrine\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\Common\Collections\ArrayCollection;

class Tag extends EntityRepository
{
	/**
	 * 
	 * Enter description here ...
	 * @param array $tagIds
	 */
	public function getTagsCollectionByTagIds($tagIds) {
		$tags = $this->findBy(array('tagId' => $tagIds));
		$tags = new ArrayCollection($tags);
		return $tags;
	}	
    
}
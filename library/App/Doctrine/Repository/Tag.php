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
	public function getTagsByTagIds(array $tagIds) 
	{
		return $this->findBy(array('tagId' => $tagIds));
	}	
    
}
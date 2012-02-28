<?php
namespace App\Doctrine\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\Common\Collections\ArrayCollection;

// if i use namespace:
// class Shop extends EntityRepository 

class Shop extends EntityRepository
{
		
	public function getAllShop()
    {
		return $this->getEntityManager()
	    		    ->createQuery('SELECT sh FROM App\Doctrine\Entity\Shop sh WHERE sh._shopId = 127')
	    		    ->getResult();
    }
    
    public function getAllPartial()
    {
		return $this->getEntityManager()
	    		    ->createQuery('SELECT PARTIAL sh.{_shopId} FROM App\Doctrine\Entity\Shop sh WHERE sh._shopId = 127')
	    		    ->getResult();
    }
    
    public function getAllQueryBuilder()
    {
		return $this->createQueryBuilder('sh')
                    ->where('sh._zipCode = ?1')
	    		    ->setParameter(1, '12345')
	    		    ->getQuery()
	    		    ->getResult();
    }
}
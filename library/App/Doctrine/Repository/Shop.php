<?php
namespace App\Doctrine\Repository;

use Doctrine\ORM\EntityRepository;

// if i use namespace:
// class Shop extends EntityRepository 

class Shop extends EntityRepository
{
    public function getAllShop()
    {
	    $qb = $this->_em->createQueryBuilder();
	    $qb->select('s')
	        ->from('Entity\Shop', 's')
	        ->orderBy('s.name');
	 
	    return $qb->getQuery()->getResult();
    }
}
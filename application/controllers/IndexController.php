<?php
class IndexController extends Zend_Controller_Action
{

    public function init()
    {
    }

    public function indexAction()
    {
			try {
				
				$entityManager = $this->getInvokeArg('bootstrap')->entityManagers['shop'];

				// $class = $entityManager->getReference('App\Doctrine\Entity\Shop', 5);
				// echo get_class($class);				
				
				try {
				$shop = new App\Doctrine\Entity\Shop();
		    	$shop->setAddress('Address');
		    	$shop->setName('Name');
		    	$shop->setZipCode('12345');
		    	// $entityManager->persist($shop);
				// $entityManager->flush();    	
				} catch (Exception $e) {
					echo $e->getMessage();
				}
				
				$tag = new App\Doctrine\Entity\Tag();
		    	$tag->setName('Name');
		    	// $entityManager->persist($tag);
				// $entityManager->flush();  

				$shop->addTag($tag);
		    	$entityManager->persist($shop);
				$entityManager->flush();  				
				
		    	$tag->setName('Nameen');
		    	$tag->setTranslatableLocale('en_en');
		    	$entityManager->persist($tag);
				$entityManager->flush();   
				
			$repository = $entityManager->getRepository('Gedmo\Translatable\Entity\Translation');
			$translations = $repository->findTranslations($tag);
			foreach ($translations as $locale => $value) {
				echo $locale . '=>' . $value['_name'];
			}
			/* $translations contains:
			Array (
			    [de_de] => Array
			        (
			            [title] => my title in de
			            [content] => my content in de
			        )
			
			    [en_us] => Array
			        (
			            [title] => my title in en
			            [content] => my content in en
			        )
			)*/
				
				
				/*
				foreach ($entityManager->getRepository('App\Doctrine\Entity\Shop')->findAll() as $shop) {
					echo $shop->getAddress() . '<br />';
				}
				
				foreach ($entityManager->getRepository('App\Doctrine\Entity\Shop')->findBy(array('_zipCode' => '12345')) as $shop) {
					echo '>dddd' . $shop->getAddress() . '<br />';
				}
				
				foreach ($entityManager->getRepository('App\Doctrine\Entity\Shop')->getAllShop() as $shop) {
					echo '>aaaa' . $shop->getAddress() . '<br />';
				}
				
				foreach ($entityManager->getRepository('App\Doctrine\Entity\Shop')->getAllPartial() as $shop) {
					echo '>bbbb' . $shop->getAddress() . '<br />';
				}
				
				foreach ($entityManager->getRepository('App\Doctrine\Entity\Shop')->getAllQueryBuilder() as $shop) {
					echo '>cccc' . $shop->getAddress() . '<br />';
				}				

				/*foreach ($entityManager->getRepository('App\Doctrine\Entity\Shop')->findByZipCode('12345') as $shop) {
					echo '>cccc' . $shop->getAddress() . '<br />';
				}*/							
				
			} catch (Exception $e) {
				
			    echo $e->getMessage();
			
			}

    }

}


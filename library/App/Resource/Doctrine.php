<?php
/**
 *
 */
class App_Resource_Doctrine extends Zend_Application_Resource_ResourceAbstract
{

    /**
     *
     */
    public $_explicitType = 'App_Resource_Doctrine';

    /*
    public function init() {

        $options = $this->getOptions();

        // include Doctrine's class loader
        require $options['libraryPath'] . '/Doctrine/Common/ClassLoader.php';
        $classLoader = new \Doctrine\Common\ClassLoader('Doctrine', $options['libraryPath']);
        // register Doctrine's class loader on SPL autoload stack
        $classLoader->register();   
                
        // get the Doctrine configuration object
        $config = new \Doctrine\ORM\Configuration;

        // cache settings
        $cache = new $options['cacheObject'];
        // caching for metadata information
        // see: http://docs.doctrine-project.org/projects/doctrine-orm/en/2.0.x/reference/configuration.html#metadata-cache-recommended
        $config->setMetadataCacheImpl($cache);
        // caching for DQL queries
        // see: http://docs.doctrine-project.org/projects/doctrine-orm/en/2.0.x/reference/configuration.html#query-cache-recommended
        $config->setQueryCacheImpl($cache);

        // sets mapping drivers
        // see: http://docs.doctrine-project.org/projects/doctrine-orm/en/2.0.x/reference/configuration.html#metadata-driver-required
        // by now there's support only for annotation driver
        $driver = $config->newDefaultAnnotationDriver($options['pathsToMappingFiles']);
        $config->setMetadataDriverImpl($driver);
		
        // $driverImpl = new \Doctrine\ORM\Mapping\Driver\XmlDriver(array(__DIR__ . '/application/doctrine/xml'));
		// $config->setMetadataDriverImpl($driverImpl);

        // sets the directory where Doctrine generates any proxy classes
        // see: http://docs.doctrine-project.org/projects/doctrine-orm/en/2.0.x/reference/configuration.html#proxy-directory-required
        $config->setProxyDir($options['proxyDirectory']);

        // sets the namespace to use for generated proxy classes
        // see: http://docs.doctrine-project.org/projects/doctrine-orm/en/2.0.x/reference/configuration.html#proxy-namespace-required
        $config->setProxyNamespace($options['proxyNamespace']);

        // sets autogeneration of proxy classes
        // see: http://docs.doctrine-project.org/projects/doctrine-orm/en/2.0.x/reference/configuration.html#auto-generating-proxy-classes-optional
        $config->setAutoGenerateProxyClasses((bool)$options['autoGenerateProxyClasses']);

        // optional logger instance
        // see: http://docs.doctrine-project.org/projects/doctrine-orm/en/2.0.x/reference/configuration.html#sql-logger-optional
        if (false === empty($options['loggerObject'])) {
            $logger = new $options['loggerObject'];
            $config->setSQLLogger($logger);
        }

        // array to store entityManager
        // there is a entityManager for each Db
        // Db with Master/Slave Replication share the same entityManager
        // through Doctrine\DBAL\Connections\MasterSlaveConnection
        $entityManagers = array();

        foreach ($options['conn'] as $dbKey => $dbProperties) {

            if (1 === count($dbProperties)) {

                // no Replication, prepares the params for a simple connection
                $params = current($dbProperties);


            } else {

                // Replication is set, prepares the params for a Master/Slave connection
                $params = array(
                    'wrapperClass' => 'Doctrine\DBAL\Connections\MasterSlaveConnection',
                    'master' => $dbProperties['master'],
                    'driver' => $dbProperties['master']['driver'],
                );
                while (FALSE !== ($slave = next($dbProperties))) {
                    $params['slaves'][] = $slave;
                }
            
                
            }

            // sets the connection object via DriverManager
            $connection = \Doctrine\DBAL\DriverManager::getConnection($params, $config);

            // sets the entityManager associative array
            // with dbConnectionName as key and EntityManager object as values
            $entityManagers[$dbKey] = \Doctrine\ORM\EntityManager::create($connection, $config);
            
        }

        // puts the array of entityManagers objects into the Zend_Registry
        $this->getBootstrap()->entityManagers = $entityManagers;
        
        // returns the array of entityManagers objects
        return $entityManagers;
    } 
    */
    
    
    
	public function init() {

        $options = $this->getOptions();

        // include Doctrine's class loader
        require $options['libraryPath'] . '/Doctrine/Common/ClassLoader.php';
        
        // Loads the Doctrine Class Loader Component
        $classLoader = new \Doctrine\Common\ClassLoader('Doctrine', $options['libraryPath']);
        // register Doctrine's class loader on SPL autoload stack
        $classLoader->register();
                        
		// Loads the Gedmo Extensions Component
		$gedmo = new \Doctrine\Common\ClassLoader('Gedmo', APPLICATION_PATH . '/../library/Doctrine');
		// Register Symfony Doctrine Component
		$gedmo->register();      
		
		// autoloader for Entity namespace
		$entity = new \Doctrine\Common\ClassLoader('Entity', APPLICATION_PATH . '/../library/App/Doctrine');
		$entity->register();		
		
		// standard doctrine annotations
		Doctrine\Common\Annotations\AnnotationRegistry::registerFile(
			APPLICATION_PATH . '/../library/Doctrine/ORM/Mapping/Driver/DoctrineAnnotations.php'
		);	

		Doctrine\Common\Annotations\AnnotationRegistry::registerFile(
			APPLICATION_PATH . '/../library/Doctrine/Gedmo/Mapping/Driver/GedmoAnnotations.php'			
		);
		
        // instantiating the cache object
        $cache = new $options['cacheObject'];	

        $annotationReader = new Doctrine\Common\Annotations\AnnotationReader;
        
        $cachedAnnotationReader = new Doctrine\Common\Annotations\CachedReader(
        						      $annotationReader, 
        						      $cache
								  );		        
		
		$driverChain = new Doctrine\ORM\Mapping\Driver\DriverChain;	

        $annotationDriver = new Doctrine\ORM\Mapping\Driver\AnnotationDriver(
        						$cachedAnnotationReader,
        						array(
            						APPLICATION_PATH . '/../library/Doctrine/Gedmo/Translatable/Entity',
            						APPLICATION_PATH . '/../library/Doctrine/Gedmo/Loggable/Entity',
            						APPLICATION_PATH . '/../library/Doctrine/Gedmo/Tree/Entity',
            						APPLICATION_PATH . '/../library/Doctrine/Gedmo/Sortable/Entity',
        						)
							);
        $driverChain->addDriver($annotationDriver, 'Gedmo');		
		
		$annotationDriver = new Doctrine\ORM\Mapping\Driver\AnnotationDriver(
			                    $cachedAnnotationReader,
			                    APPLICATION_PATH . '/../library/App/Doctrine/Entity'
		                    );
		$driverChain->addDriver($annotationDriver, 'App\Doctrine\Entity');

        // get the Doctrine configuration object
        $config = new \Doctrine\ORM\Configuration;

        // caching for metadata information
        // see: http://docs.doctrine-project.org/projects/doctrine-orm/en/2.0.x/reference/configuration.html#metadata-cache-recommended
        $config->setMetadataCacheImpl($cache);
        // caching for DQL queries
        // see: http://docs.doctrine-project.org/projects/doctrine-orm/en/2.0.x/reference/configuration.html#query-cache-recommended
        $config->setQueryCacheImpl($cache);
                
        $config->setMetadataDriverImpl($driverChain);
        
        // sets the directory where Doctrine generates any proxy classes
        // see: http://docs.doctrine-project.org/projects/doctrine-orm/en/2.0.x/reference/configuration.html#proxy-directory-required
        $config->setProxyDir($options['proxyDirectory']);

        // sets the namespace to use for generated proxy classes
        // see: http://docs.doctrine-project.org/projects/doctrine-orm/en/2.0.x/reference/configuration.html#proxy-namespace-required
        $config->setProxyNamespace($options['proxyNamespace']);

        // sets autogeneration of proxy classes
        // see: http://docs.doctrine-project.org/projects/doctrine-orm/en/2.0.x/reference/configuration.html#auto-generating-proxy-classes-optional
        $config->setAutoGenerateProxyClasses((bool)$options['autoGenerateProxyClasses']);

        // optional logger instance
        // see: http://docs.doctrine-project.org/projects/doctrine-orm/en/2.0.x/reference/configuration.html#sql-logger-optional
        if (false === empty($options['loggerObject'])) {
            $logger = new $options['loggerObject'];
            $config->setSQLLogger($logger);
        }
        
		$evm = new Doctrine\Common\EventManager();
		
		// sluggable
		$listener = new Gedmo\Sluggable\SluggableListener;
		$listener->setAnnotationReader($cachedAnnotationReader);
		$evm->addEventSubscriber($listener);
		
		// tree
		$listener = new Gedmo\Tree\TreeListener;
		$listener->setAnnotationReader($cachedAnnotationReader);
		$evm->addEventSubscriber($listener);
		
		// loggable
		$listener = new Gedmo\Loggable\LoggableListener;
		$listener->setAnnotationReader($cachedAnnotationReader);
		$evm->addEventSubscriber($listener);		

		// timestampable
		$listener = new Gedmo\Timestampable\TimestampableListener;
		$listener->setAnnotationReader($cachedAnnotationReader);
		$evm->addEventSubscriber($listener);	
		
		// translatable
		$listener = new Gedmo\Translatable\TranslationListener;
		$listener->setTranslatableLocale('en');
		$listener->setDefaultLocale('en');
		$listener->setAnnotationReader($cachedAnnotationReader);
		$evm->addEventSubscriber($listener);
		
		// sortable
		$listener = new Gedmo\Sortable\SortableListener;
		$listener->setAnnotationReader($cachedAnnotationReader);
		$evm->addEventSubscriber($listener);	
		   
        // array to store entityManager
        // there is a entityManager for each Db
        // Db with Master/Slave Replication share the same entityManager
        // through Doctrine\DBAL\Connections\MasterSlaveConnection
        $entityManagers = array();

        foreach ($options['conn'] as $dbKey => $dbProperties) {

            if (1 === count($dbProperties)) {

                // no Replication, prepares the params for a simple connection
                $params = current($dbProperties);


            } else {

                // Replication is set, prepares the params for a Master/Slave connection
                $params = array(
                    'wrapperClass' => 'Doctrine\DBAL\Connections\MasterSlaveConnection',
                    'master' => $dbProperties['master'],
                    'driver' => $dbProperties['master']['driver'],
                );
                while (FALSE !== ($slave = next($dbProperties))) {
                    $params['slaves'][] = $slave;
                }
                            
            }

            // sets the connection object via DriverManager
            $connection = \Doctrine\DBAL\DriverManager::getConnection($params, $config, $evm);

            // sets the entityManager associative array
            // with dbConnectionName as key and EntityManager object as values
            $entityManagers[$dbKey] = \Doctrine\ORM\EntityManager::create($connection, $config, $evm);
            
        }

        // puts the array of entityManagers objects into the Zend_Registry
        $this->getBootstrap()->entityManagers = $entityManagers;
        
        // returns the array of entityManagers objects
        return $entityManagers;
	}
    
}

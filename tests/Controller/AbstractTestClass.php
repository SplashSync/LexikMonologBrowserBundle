<?php

namespace Splash\SonataAdminMonologBundle\Tests\Controller;

use Doctrine\ORM\EntityManagerInterface;

use Symfony\Bridge\Monolog\Logger;
use Symfony\Bundle\FrameworkBundle\Client;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

use Splash\SonataAdminMonologBundle\Repository\LogRepository;
use Splash\SonataAdminMonologBundle\Entity\Log;

/**
 * Base Class for Bundle Tests
 */
abstract class AbstractTestClass extends WebTestCase
{

    /**
     * @var Client
     */
    protected $client       =   Null;
    
    /**
     * @var EntityManagerInterface
     */
    protected $entityManager; 

    /**
     * @var LogRepository
     */
    protected $repository; 
    
    /**
     * @var Logger
     */
    protected $Logger; 
    
    
    /**
     * {@inheritDoc}
     */
    protected function setUp()
    {
        $this->client = static::createClient();
        //====================================================================//
        // Link to entity manager Services
        $this->entityManager  = $this->client->getContainer()->get('doctrine')->getManager();  
        //====================================================================//
        // Link to Logs Repository
        $this->repository  = $this->client->getContainer()->get('doctrine')->getRepository("SplashSonataAdminMonologBundle:Log");  
        //====================================================================//
        // Connect to Logger
        $this->Logger =   $this->client->getContainer()->get('monolog.logger.phpunit');        
    }     
    
    /**
     * @abstract    Dummy Test
     */
    public function testPhpUnitIsWorking()
    {
        $this->assertTrue(true);
    }     
    
    /**
     * Delete All Logs Entities
     */
    public function cleanup()
    {
        $this->repository->removeAll();
        $this->assertEmpty($this->repository->findAll());
    } 

    /**
     * Verify a First Log item
     * 
     * @param Log $log
     * @param int $level
     * @param string $name
     * @param string $channel
     * 
     * @return Log
     */
    public function verifyFirst(int $level = null, string $name = null, string $channel = null)
    {
        //====================================================================//
        // Load First Log Item
        $logs = $this->repository->findAll();
        $log = array_shift($logs);
        //====================================================================//
        // Verify
        $this->assertNotEmpty($log);
        $this->verify($log, $level, $name, $channel);
        
        return $log;
    }     
    
    
    /**
     * Verify a Log item
     * 
     * @param Log $log
     * @param int $level
     * @param string $name
     * @param string $channel
     */
    public function verify(Log $log, int $level = null, string $name = null, string $channel = null)
    {
        $this->assertNotEmpty($log->getDateTime());
        $this->assertNotEmpty($log->getFormated());
        
        if(!is_null($level)) {
            $this->assertSame($level, $log->getLevel());
        }
        if(!is_null($name)) {
            $this->assertSame($name, $log->getLevelName());
        }
        if(!is_null($channel)) {
            $this->assertSame($channel, $log->getChannel());
        }
    }     
    
}

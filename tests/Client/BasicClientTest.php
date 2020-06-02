<?php
namespace HedgebotApi\Tests\Client;

use HedgebotApi\Client;
use PHPUnit\Framework\TestCase;

/**
 * Basic client text.
 * 
 * @package HedgebotApi\Tests
 */
class BasicClientTest extends TestCase
{
    /** @var Client $client */
    protected static $client;

    /**
     * Set up the client for all the tests.
     * 
     * @return void 
     */
    public static function setUpBeforeClass(): void
    {
        self::$client = new Client(HEDGEBOT_BASE_URL, HEDGEBOT_TOKEN);
    }

    /**
     * Tests a client call to the Hedgebot API
     * 
     * @return void 
     */
    public function testClientCall()
    {
        $pluginEndpoint = self::$client->endpoint('/plugin');

        $pluginList = $pluginEndpoint->getList();
        $this->assertIsArray($pluginList);
    }
}
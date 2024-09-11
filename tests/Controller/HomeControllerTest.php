<?php

namespace App\Tests\Controller;

use App\DataFixtures\AppFixtures;
use Liip\TestFixturesBundle\Services\DatabaseToolCollection;
use Liip\TestFixturesBundle\Services\DatabaseTools\AbstractDatabaseTool;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class HomeControllerTest extends WebTestCase
{
    /** @var AbstractDatabaseTool */
    protected static $databaseTool;

    public static function setUpBeforeClass(): void
    {
        parent::setUpBeforeClass();
        self::$databaseTool = static::getContainer()->get(DatabaseToolCollection::class)->get();
    }

    protected function setUp(): void
    {
        parent::setUp();
        self::$databaseTool->loadFixtures([
            AppFixtures::class
        ]);
    }

    public function testSomething(): void
    {
        $client = self::createClient();
        $crawler = $client->request('GET', '/');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Hello World');
    }
}

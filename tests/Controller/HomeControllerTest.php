<?php

namespace App\Tests\Controller;

use App\DataFixtures\AppFixtures;
use App\Repository\UserRepository;
use Liip\TestFixturesBundle\Services\DatabaseToolCollection;
use Liip\TestFixturesBundle\Services\DatabaseTools\AbstractDatabaseTool;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class HomeControllerTest extends WebTestCase
{
    /** @var AbstractDatabaseTool */
    protected static $databaseTool;
    private $client;

    // public static function setUpBeforeClass(): void
    // {
    //     parent::setUpBeforeClass();
    //     self::$databaseTool = static::getContainer()->get(DatabaseToolCollection::class)->get();
    // }

    protected function setUp(): void
    {
        $this->client = self::createClient();
        self::$databaseTool = static::getContainer()->get(DatabaseToolCollection::class)->get();
        self::$databaseTool->loadFixtures([
            AppFixtures::class
        ]);
    }

    public function testSomething(): void
    {
        $crawler = $this->client->request('GET', '/');
        $userRepository = static::getContainer()->get(UserRepository::class);

        $testUser = $userRepository->getByEmail('aaa@12aaa.com');
        $this->assertResponseIsSuccessful();
        $this->client->loginUser($this->$testUser);
        // $this->assertSelectorTextContains('h1', 'Hello World');
    }
}

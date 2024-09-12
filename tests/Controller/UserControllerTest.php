<?php

namespace App\Tests\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UserControllerTest extends WebTestCase
{
    public function testUserSettings()
    {
        $client = static::createClient();

        $userRepository = static::getContainer()->get('doctrine')->getRepository(User::class);
        $testUser = $userRepository->findOneBy(['email' => 'aaa@12aaa.com']);
        $client->loginUser($testUser);

        $client->request('GET', '/user/settings');

        $this->assertResponseIsSuccessful();
    }

    public function testUserSettingsForm()
    {
        $client = static::createClient();

        $userRepository = static::getContainer()->get('doctrine')->getRepository(User::class);
        $testUser = $userRepository->findOneBy(['email' => 'aaa@12aaa.com']);
        $client->loginUser($testUser);

        $crawler = $client->request('GET', '/user/settings');

        $form = $crawler->selectButton('Enregistrer')->form([
            'user[telephone]' => '0185617435',
            'user[current_password]' => 'aaaaaa',
        ]);

        $client->submit($form);

        $this->assertResponseRedirects('/');
        $client->followRedirect();
    }

}

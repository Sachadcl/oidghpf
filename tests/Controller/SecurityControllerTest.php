<?php

namespace App\Tests\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class SecurityControllerTest extends WebTestCase
{
    public function testLogin()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/login');

        $this->assertResponseIsSuccessful();

        $form = $crawler->selectButton('Sign in')->form([
            'email' => 'aaa@12aaa.com',
            'password' => 'aaaaaa',
        ]);

        $client->submit($form);

        $this->assertResponseRedirects('/');
        $client->followRedirect();
    }

    public function testLoginWithInvalidCredentials()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/login');

        $this->assertResponseIsSuccessful();

        $form = $crawler->selectButton('Sign in')->form([
            'email' => 'wrongemail@example.com',
            'password' => 'wrongpassword123',
        ]);

        $client->submit($form);

        $this->assertResponseStatusCodeSame(302);
    }

    public function testLogout()
    {
        $client = static::createClient();

        $userRepository = static::getContainer()->get('doctrine')->getRepository(User::class);
        $testUser = $userRepository->getByEmail('aaa@12aaa.com');
        $client->loginUser($testUser);

        $client->request('GET', '/user/settings');
        $this->assertResponseIsSuccessful();

        $client->request('GET', '/logout');
        $this->assertResponseRedirects('/');
        $client->followRedirect();

        $client->request('GET', '/user/settings');
        $this->assertResponseRedirects('/login');
    }
}

<?php

namespace App\Tests\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AdminControllerTest extends WebTestCase
{
    private function createClientWithLogin(): \Symfony\Bundle\FrameworkBundle\KernelBrowser
    {
        $client = static::createClient();
        $userRepository = static::getContainer()->get('doctrine')->getRepository(User::class);
        $adminUser = $userRepository->findOneBy(['email' => 'bbb@12aaa.com']);
        $client->loginUser($adminUser);
        return $client;
    }

    public function testUserManagement()
    {
        $client = $this->createClientWithLogin();
        $client->request('GET', '/admin/user-management');

        $this->assertResponseIsSuccessful();
    }

    public function testDeactivateUser()
    {
        $client = $this->createClientWithLogin();
        $userRepository = static::getContainer()->get('doctrine')->getRepository(User::class);
        $user = $userRepository->findOneBy(['email' => 'aaa@12aaa.com']);

        $client->request('GET', '/admin/deactivate/' . $user->getId());
        $this->assertResponseRedirects('/admin/user-management');
        $client->followRedirect();
    }

    public function testActivateUser()
    {
        $client = $this->createClientWithLogin();
        $userRepository = static::getContainer()->get('doctrine')->getRepository(User::class);
        $user = $userRepository->findOneBy(['email' => 'aaa@12aaa.com']);

        $client->request('GET', '/admin/activate/' . $user->getId());
        $this->assertResponseRedirects('/admin/user-management');
        $client->followRedirect();
    }

    public function testDeleteUser()
    {
        $client = $this->createClientWithLogin();
        $userRepository = static::getContainer()->get('doctrine')->getRepository(User::class);
        $user = $userRepository->findOneBy(['email' => 'aaa@12aaa.com']);

        $client->request('GET', '/admin/delete/' . $user->getId());
        $this->assertResponseRedirects('/admin/user-management');
        $client->followRedirect();
    }

    public function testAddUser()
    {
        $client = $this->createClientWithLogin();
        $crawler = $client->request('GET', '/admin/add');

        $this->assertResponseIsSuccessful();
        $form = $crawler->selectButton('Ajouter')->form([
            'add_user[email]' => 'aaa@12aaa.com',
            'add_user[username]' => 'newuser',
            'add_user[last_name]' => 'User',
            'add_user[first_name]' => 'New',
            'add_user[telephone]' => '1234567890',
            'add_user[password]' => 'aaaaaa',
            'add_user[confirmation]' => 'aaaaaa',
        ]);

        $client->submit($form);
        $this->assertResponseRedirects('/admin/user-management');
        $client->followRedirect();
    }
}

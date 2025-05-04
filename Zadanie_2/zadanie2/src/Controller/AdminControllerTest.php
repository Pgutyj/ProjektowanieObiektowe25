<?php
// tests/Controller/AdminControllerTest.php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use App\Entity\User;

class AdminControllerTest extends WebTestCase
{
    public function testAdminPanelAccess(): void
    {
        $client = static::createClient();
        $user = self::getContainer()->get('doctrine')->getRepository(User::class)->find(12);

        $user->setRoles(['ROLE_ADMIN']); // upewnij się, że ma rolę admina

        $client->loginUser($user);

        $client->request('GET', '/admin');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorExists('ul'); // lub
        $this->assertSelectorExists('li'); // jeśli chcesz się upewnić, że są użytkownicy// przykładowe sprawdzenie zawartości
    }
}

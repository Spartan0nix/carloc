<?php

namespace App\Tests\Controller;

use App\Tests\FixtureTrait;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UserControllerTest extends WebTestCase
{
    use FixtureTrait;

    public function testAccount(): void {
        $client = $this->createClient();
        $data = $this->load(['user'], $client->getContainer());
        $client->followRedirects();

        $client->request('GET', '/compte');
        $this->assertEquals(401, $client->getResponse()->getStatusCode());

        $client->loginUser($data['test']);
        $client->request('GET', '/compte');
        $this->assertSelectorTextContains('h2', 'Mon compte');
    }
}
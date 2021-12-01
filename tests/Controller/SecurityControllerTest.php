<?php

namespace App\Tests\Controller;

use App\Tests\FixtureTrait;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class SecurityControllerTest extends WebTestCase
{
    use FixtureTrait;

    public function testLogin(): void {
        $client = $this->createClient();
        $this->load(['user'], $client->getContainer());
        $crawler = $client->request('GET', '/connexion');

        $button = $crawler->selectButton('btn-form-submit');
        $client->followRedirects();

        $form = $button->form();
        $form['email'] = 'test@local.com';
        $form['password'] = 'password';
        $client->submit($form);

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h2', 'Mon compte');
    }
}
<?php

namespace App\Tests\Controller;

use App\Entity\User;
use App\Tests\FixtureTrait;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class SecurityControllerTest extends WebTestCase
{
    use FixtureTrait;

    public function testLogin(): void {
        $client = $this->createClient();
        $data = $this->load(['user'], $client->getContainer());

        $crawler = $client->request('GET', '/connexion');
        $this->assertResponseIsSuccessful();

        $button = $crawler->selectButton('btn-form-submit');
        $client->followRedirects();

        $form = $button->form();
        $form['email'] = $data['admin']->getEmail();
        $form['password'] = 'password';
        $client->submit($form);

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h2', 'Mon compte');
    }

    public function testRegister(): void {
        $client = $this->createClient();
        $data = $this->load(['city', 'department'], $client->getContainer());
        $crawler = $client->request('GET', '/nouveau-compte');
        
        $button = $crawler->selectButton('btn-form-submit');
        $client->followRedirects();

        $form = $button->form();
        $form['auth_register[last_name]'] = 'test';
        $form['auth_register[first_name]'] = 'register';
        $form['auth_register[email]'] = 'test_register@local.com';
        $form['auth_register[password]'] = 'password';
        $form['auth_register[address]'] = 'test register 1025';
        $form['auth_register[city_id]'] = $data['city1']->getId();
        $form['auth_register[department_id]'] = $data['department1']->getId();
        $client->submit($form);

        $em = $client->getContainer()->get('doctrine.orm.default_entity_manager');
        $user = $em->getRepository(User::class)->findOneBy(['email' => 'test_register@local.com']);

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h2', 'Mon compte');
        $this->assertEquals('test', $user->getLastName());
        $this->assertEquals('register', $user->getFirstName());
        $this->assertEquals('test_register@local.com', $user->getEmail());
        $this->assertEquals('test register 1025', $user->getAddress());
        $this->assertEquals($data['city1']->getId(), $user->getCityId()->getId());
        $this->assertEquals($data['department1']->getId(), $user->getDepartmentId()->getId());
    }

    public function testLogout(): void {
        $client = $this->createClient();
        $data = $this->load(['user'], $client->getContainer());

        $client->loginUser($data['test']);
        $client->followRedirects();

        $client->request('GET', '/compte');
        $this->assertResponseIsSuccessful();

        $client->request('GET', '/logout');
        $client->request('GET', '/compte');
        $this->assertEquals(401 ,$client->getResponse()->getStatusCode());
    }
}
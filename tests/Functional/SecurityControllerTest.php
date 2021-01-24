<?php


namespace App\Tests\Functional;

use App\Entity\Task;
use App\Entity\User;
use App\Tests\FakeDataTrait\FakeData;
use App\Tests\FakeDataTrait\TruncateDB;
use App\Tests\FakeDataTrait\Connection;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class SecurityControllerTest extends WebTestCase
{
    public function testLogin()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/login');
        static::assertSame(200, $client->getResponse()->getStatusCode());

        // Test if login field exists
        static::assertSame(1, $crawler->filter('input[name="_username"]')->count());
        static::assertSame(1, $crawler->filter('input[name="_password"]')->count());

        $form = $crawler->selectButton('Se connecter')->form();
        $form['_username'] = 'user';
        $form['_password'] = 'test';
        $client->submit($form);

        $crawler = $client->followRedirect();
        static::assertSame(200, $client->getResponse()->getStatusCode());

        // Test if home page text when authenticated exists
        static::assertSame("Bienvenue sur Todo List, l'application vous permettant de gérer l'ensemble de vos tâches sans effort !", $crawler->filter('h1')->text());

        // Return the client to reuse the authenticated user it in others functionnal tests
        return $client;
    }

    public function testLoginAsAdmin()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/login');
        static::assertSame(200, $client->getResponse()->getStatusCode());

        // Test if login field exists
        static::assertSame(1, $crawler->filter('input[name="_username"]')->count());
        static::assertSame(1, $crawler->filter('input[name="_password"]')->count());

        $form = $crawler->selectButton('Se connecter')->form();
        $form['_username'] = 'admin';
        $form['_password'] = 'test';
        $client->submit($form);

        $crawler = $client->followRedirect();
        static::assertSame(200, $client->getResponse()->getStatusCode());

        // Test if home page text when authenticated exists
        static::assertSame("Bienvenue sur Todo List, l'application vous permettant de gérer l'ensemble de vos tâches sans effort !", $crawler->filter('h1')->text());

        // Return the client to reuse the authenticated user admin it in others functionnal tests
        return $client;
    }

    public function testLoginWithWrongCredidentials()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/login');
        static::assertSame(200, $client->getResponse()->getStatusCode());

        // Test if login field exists
        static::assertSame(1, $crawler->filter('input[name="_username"]')->count());
        static::assertSame(1, $crawler->filter('input[name="_password"]')->count());

        $form = $crawler->selectButton('Se connecter')->form();
        $form['_username'] = 'user';
        $form['_password'] = 'WrongPassword';
        $client->submit($form);

        $crawler = $client->followRedirect();
        static::assertSame(200, $client->getResponse()->getStatusCode());

        // Test if error message is displayed
        static::assertSame("Invalid credentials.", $crawler->filter('div.alert.alert-danger')->text());
    }
}